
document.addEventListener("DOMContentLoaded", () => {

  /* ---------- Slider / Overlay (null-safe) ---------- */
  const toggleBtn = document.getElementById("toggleBtn");
  const closeBtn  = document.getElementById("closeBtn");
  const slider    = document.getElementById("slider");
  const overlay   = document.getElementById("overlay");

  if (toggleBtn && slider && overlay) {
    toggleBtn.addEventListener("click", () => {
      slider.classList.add("active");
      overlay.classList.add("active");
      document.body.style.overflow = "hidden"; // optional: prevent background scroll
    });
  } else {
    // optional debug
    if (!toggleBtn) console.warn("toggleBtn not found");
    if (!slider)   console.warn("slider not found");
    if (!overlay)  console.warn("overlay not found");
  }

  if (closeBtn && slider && overlay) {
    closeBtn.addEventListener("click", () => {
      slider.classList.remove("active");
      overlay.classList.remove("active");
      document.body.style.overflow = ""; // restore scroll
    });
    overlay.addEventListener("click", () => {
      slider.classList.remove("active");
      overlay.classList.remove("active");
      document.body.style.overflow = "";
    });
  }

  /* ---------- Level buttons: event-driven (no polling) ---------- */
  const levelButtons = Array.from(document.querySelectorAll(".level-btn")); // NodeList -> Array
  const maxLevels = 3;

  // Helper: show only the requested Levelteam panel; hide others
  function showLevelPanel(level) {
    const n = parseInt(level, 10);
    if (Number.isNaN(n)) return;
    for (let i = 1; i <= maxLevels; i++) {
      const panel = document.getElementById("Levelteam" + i);
      if (!panel) continue; // skip if missing
      if (i === n) panel.classList.remove("hidden");
      else panel.classList.add("hidden");
    }
  }

  // Attach click listeners to level buttons; use data-level or fallback to id numeric
  levelButtons.forEach(btn => {
    btn.addEventListener("click", () => {
      // toggle active class on buttons
      levelButtons.forEach(b => b.classList.remove("activebtn"));
      btn.classList.add("activebtn");

      // determine level number: prefer data-level
      const level = btn.dataset.level || btn.getAttribute("data-level") || (btn.id && btn.id.replace(/\D/g, ""));
      showLevelPanel(level);
    });
  });

  // Initialize visible panel from existing active button (if any), else hide all except 1
  const initial = document.querySelector(".level-btn.activebtn") || levelButtons[0];
  if (initial) {
    const initLevel = initial.dataset.level || initial.getAttribute("data-level") || (initial.id && initial.id.replace(/\D/g, ""));
    // ensure activebtn is set
    levelButtons.forEach(b => b.classList.remove("activebtn"));
    initial.classList.add("activebtn");
    showLevelPanel(initLevel);
  } else {
    // no level buttons found -> attempt to hide all Levelteam panels
    for (let i = 1; i <= maxLevels; i++) {
      const panel = document.getElementById("Levelteam" + i);
      if (panel) panel.classList.add("hidden");
    }
  }

  /* ---------- Circular progress function (null-safe) ---------- */
  function updateCircle(id, current, max, amount) {
    const circle = document.getElementById(id);
    const valueText = document.getElementById("value" + id.slice(-1));
    const amountText = document.getElementById("amount" + id.slice(-1));
    if (!circle) {
      console.warn("Missing circle element:", id);
      return;
    }
    // sanitize numbers
    current = Number(current) || 0;
    max = Number(max) || 1;
    amount = Number(amount) || 0;

    let percent = Math.min(Math.round((current / max) * 100), 100);
    const deg = (percent / 100) * 360;

    circle.style.background = `conic-gradient(yellow ${deg}deg, white ${deg}deg)`;
    if (valueText)  valueText.textContent = percent + "%";
    if (amountText) amountText.textContent = amount + "$";
  }

  /* ---------- Read hidden values safely and call update ---------- */
  const selfEl = document.getElementById("selfpurchase");
  const teamEl = document.getElementById("teampurchase");

  const selfPurchaseCurrent = selfEl ? parseInt(selfEl.innerText.trim(), 10) || 0 : 0;
  const teamPurchaseCurrent = teamEl ? parseInt(teamEl.innerText.trim(), 10) || 0 : 0;

  // set these to whatever maxima you need
  const SELF_MAX = 300;   // change to 165 if you want, set as required
  const TEAM_MAX = 1650;

  updateCircle("circle1", selfPurchaseCurrent, SELF_MAX, selfPurchaseCurrent);
  updateCircle("circle2", teamPurchaseCurrent, TEAM_MAX, teamPurchaseCurrent);

  /* ---------- DEBUG helper (optional) ---------- */
  // If you still see errors, uncomment this to quickly show missing IDs:
  /*
  const requiredIds = ["Lbtn1","Lbtn2","Lbtn3","Lbtn4","Lbtn5","Lbtn6",
                       "Levelteam1","Levelteam2","Levelteam3","Levelteam4","Levelteam5","Levelteam6"];
  requiredIds.forEach(id=>{
    if (!document.getElementById(id)) console.info("Missing element id:", id);
  });
  */

}); // end DOMContentLoaded
document.getElementById("roioninvest").addEventListener("click", function () {
    window.location.href = "earn.php"; 
});
