document.addEventListener("DOMContentLoaded", () => {
  const sectionIds = [
    "section-mouvements",
    "section-documents",
    "section-details",
    "section-document-details",
  ];

  const sections = sectionIds
    .map((id) => document.getElementById(id))
    .filter(Boolean);

  const triggers = Array.from(
    document.querySelectorAll("[data-target-section]"),
  );

  const tabIds = ["tab-grid", "tab-docs"];
  const tabs = tabIds.map((id) => document.getElementById(id)).filter(Boolean);

  const showSection = (targetId) => {
    sections.forEach((section) => {
      section.classList.toggle("hidden", section.id !== targetId);
    });

    tabs.forEach((tab) => {
      const isActive = tab.dataset.targetSection === targetId;
      tab.classList.toggle("border-primary", isActive);
      tab.classList.toggle("text-primary", isActive);
      tab.classList.toggle("border-transparent", !isActive);
    });
  };

  triggers.forEach((trigger) => {
    trigger.addEventListener("click", () => {
      const targetId = trigger.dataset.targetSection;
      if (!targetId) return;
      showSection(targetId);
    });
  });

  showSection("section-mouvements");
});
