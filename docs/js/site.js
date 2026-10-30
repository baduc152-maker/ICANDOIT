/* Tương tác giao diện website ICANDOIT: menu di động và hiệu ứng xuất hiện. */
(function () {
    'use strict';

    // --- Menu điều hướng trên thiết bị di động -----------------------------
    var toggle = document.querySelector('[data-nav-toggle]');
    var nav = document.querySelector('[data-nav]');

    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            var open = nav.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });

        nav.addEventListener('click', function (event) {
            if (event.target.tagName === 'A') {
                nav.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });

        document.addEventListener('click', function (event) {
            if (!nav.contains(event.target) && !toggle.contains(event.target)) {
                nav.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // --- Hiệu ứng xuất hiện khi cuộn trang ---------------------------------
    var targets = document.querySelectorAll('.reveal');

    if (!targets.length) {
        return;
    }

    if (!('IntersectionObserver' in window)) {
        targets.forEach(function (el) { el.classList.add('is-visible'); });
        return;
    }

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { rootMargin: '0px 0px -60px 0px', threshold: 0.08 });

    targets.forEach(function (el) { observer.observe(el); });
})();
