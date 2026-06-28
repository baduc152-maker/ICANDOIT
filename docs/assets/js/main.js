// ICANDOIT Academic English — interactions
(function () {
  "use strict";

  // Year in footer
  var y = document.getElementById("year");
  if (y) y.textContent = new Date().getFullYear();

  // Mobile nav toggle
  var toggle = document.getElementById("navToggle");
  var nav = document.getElementById("nav");
  if (toggle && nav) {
    toggle.addEventListener("click", function () {
      var open = nav.classList.toggle("open");
      toggle.classList.toggle("open", open);
      toggle.setAttribute("aria-expanded", open ? "true" : "false");
    });
    nav.querySelectorAll("a").forEach(function (a) {
      a.addEventListener("click", function () {
        nav.classList.remove("open");
        toggle.classList.remove("open");
        toggle.setAttribute("aria-expanded", "false");
      });
    });
  }

  // Scroll reveal
  var items = document.querySelectorAll(".reveal");
  if ("IntersectionObserver" in window) {
    var io = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (e) {
          if (e.isIntersecting) {
            e.target.classList.add("in");
            io.unobserve(e.target);
          }
        });
      },
      { threshold: 0.12 }
    );
    items.forEach(function (el) {
      io.observe(el);
    });
  } else {
    items.forEach(function (el) {
      el.classList.add("in");
    });
  }

  // Lead form handler (demo — no backend). Saves locally and confirms.
  window.icandoit = window.icandoit || {};
  window.icandoit.submitLead = function (event) {
    event.preventDefault();
    var form = event.target;
    var data = {};
    new FormData(form).forEach(function (v, k) {
      data[k] = v;
    });
    try {
      var key = "icandoit_leads";
      var leads = JSON.parse(localStorage.getItem(key) || "[]");
      data.ts = new Date().toISOString();
      leads.push(data);
      localStorage.setItem(key, JSON.stringify(leads));
    } catch (e) {
      /* ignore storage errors */
    }
    var status = form.querySelector(".form-note");
    if (status) {
      status.textContent =
        "✅ Cảm ơn " + (data.name || "bạn") + "! ICANDOIT sẽ liên hệ trong 24h.";
      status.style.color = "#16a34a";
    } else {
      alert("Cảm ơn bạn! ICANDOIT sẽ liên hệ trong 24h.");
    }
    form.reset();
    return false;
  };
})();
