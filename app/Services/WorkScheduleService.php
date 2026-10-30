<?php

namespace App\Services;

use App\Models\TimetableEntry;
use App\Models\WorkTask;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Dựng lịch công việc theo tuần: ghép các buổi dạy lấy từ thời khoá biểu với
 * các công việc được giao trong cùng khoảng ngày.
 */
class WorkScheduleService
{
    /**
     * @param  int|null  $userId  Chỉ lấy lịch của một người; null là lấy tất cả.
     * @return array{start: Carbon, end: Carbon, days: array<int, array<string, mixed>>, summary: array<string, int>}
     */
    public function week(Carbon $anyDayInWeek, ?int $userId = null): array
    {
        $start = $anyDayInWeek->copy()->startOfWeek(Carbon::MONDAY)->startOfDay();
        $end = $start->copy()->addDays(6)->endOfDay();

        $entries = $this->timetableEntries($start, $end, $userId);
        $tasks = $this->tasks($start, $end, $userId);

        $days = [];
        $summary = ['classes' => 0, 'tasks' => 0, 'done' => 0];

        for ($offset = 0; $offset < 7; $offset++) {
            $date = $start->copy()->addDays($offset);
            $items = collect();

            foreach ($entries as $entry) {
                if ($this->entryRunsOn($entry, $date)) {
                    $items->push($this->classItem($entry));
                    $summary['classes']++;
                }
            }

            foreach ($tasks->get($date->toDateString(), collect()) as $task) {
                $items->push($this->taskItem($task));
                $summary['tasks']++;

                if ($task->status === WorkTask::STATUS_DONE) {
                    $summary['done']++;
                }
            }

            $days[] = [
                'date' => $date,
                'is_today' => $date->isToday(),
                'items' => $items->sortBy('sort')->values()->all(),
            ];
        }

        return [
            'start' => $start,
            'end' => $start->copy()->addDays(6),
            'days' => $days,
            'summary' => $summary,
        ];
    }

    /**
     * @return Collection<int, TimetableEntry>
     */
    private function timetableEntries(Carbon $start, Carbon $end, ?int $userId): Collection
    {
        return TimetableEntry::query()
            ->with('teacher')
            ->where('is_active', true)
            ->whereDate('effective_from', '<=', $end)
            ->where(function ($query) use ($start) {
                $query->whereNull('effective_to')->orWhereDate('effective_to', '>=', $start);
            })
            ->when($userId, fn ($query) => $query->where('user_id', $userId))
            ->orderBy('start_time')
            ->get();
    }

    /**
     * @return Collection<string, Collection<int, WorkTask>>
     */
    private function tasks(Carbon $start, Carbon $end, ?int $userId): Collection
    {
        return WorkTask::query()
            ->with(['assignee', 'assigner'])
            ->whereBetween('work_date', [$start->toDateString(), $end->toDateString()])
            ->when($userId, fn ($query) => $query->where('user_id', $userId))
            ->orderByRaw('start_time is null, start_time')
            ->get()
            ->groupBy(fn (WorkTask $task) => $task->work_date->toDateString());
    }

    private function entryRunsOn(TimetableEntry $entry, Carbon $date): bool
    {
        if ($entry->weekday !== (int) $date->isoWeekday()) {
            return false;
        }

        if ($entry->effective_from->startOfDay()->gt($date)) {
            return false;
        }

        return $entry->effective_to === null || $entry->effective_to->endOfDay()->gte($date);
    }

    /**
     * @return array<string, mixed>
     */
    private function classItem(TimetableEntry $entry): array
    {
        return [
            'type' => 'class',
            'id' => $entry->id,
            'title' => $entry->class_name,
            'subtitle' => $entry->subject,
            'time' => $entry->timeRange(),
            'sort' => substr((string) $entry->start_time, 0, 5),
            'room' => $entry->room,
            'person' => $entry->teacher?->name ?? '—',
            'status' => null,
            'priority' => null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function taskItem(WorkTask $task): array
    {
        return [
            'type' => 'task',
            'id' => $task->id,
            'title' => $task->title,
            'subtitle' => $task->description,
            'time' => $task->timeRange(),
            // Công việc không gắn giờ xếp xuống cuối ngày.
            'sort' => $task->start_time ? substr((string) $task->start_time, 0, 5) : '99:99',
            'room' => null,
            'person' => $task->assignee?->name ?? '—',
            'status' => $task->status,
            'priority' => $task->priority,
        ];
    }
}
