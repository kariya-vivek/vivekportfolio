import re

with open('user/myorders.php', 'r', encoding='utf-8', errors='ignore') as f:
    text = f.read()

bad = '''        showPopup("Feedback submitted successfully!", 2000, "#4CAF50");
        form.style.display = "none";
        form.querySelector(".feedback-text").value = "";
        form.querySelectorAll(".feedback-star").forEach(s => s.classList.remove("active"));
      } else {'''

good = '''        showPopup("Feedback submitted successfully!", 2000, "#4CAF50");
        form.style.display = "none";
        
        // Update to "View Feedback" state
        const toggleBtn = form.previousElementSibling;
        if (toggleBtn && toggleBtn.classList.contains("feedback-toggle-btn")) {
            toggleBtn.textContent = "View Feedback";
        }
        
        const textArea = form.querySelector(".feedback-text");
        if (textArea) {
            textArea.readOnly = true;
        }
        
        // Remove the submit button since it's already submitted
        const submitBtn = form.querySelector(".feedback-submit-btn");
        if (submitBtn) {
            submitBtn.style.display = "none";
        }
        
        // Disable star clicking by removing the onclick in toggleFeedbackForm
        // Just visually it remains as they selected it.
        form.classList.add("submitted"); // Optional flag
      } else {'''

if bad in text:
    text = text.replace(bad, good)
    with open('user/myorders.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Fixed submitFeedback UI update in myorders.php")
else:
    print("Not found in myorders.php")

# Wait, there's another thing: toggleFeedbackForm needs to NOT attach click events if it's already submitted or is View Feedback.
bad_toggle = '''function toggleFeedbackForm(btn) {
  const form = btn.nextElementSibling;
  form.style.display = form.style.display === "none" ? "block" : "none";

  // Attach star click events dynamically for THIS form only
  const stars = form.querySelectorAll(".feedback-star");
  stars.forEach(star => {
    star.onclick = () => {
      const val = star.getAttribute("data-value");
      stars.forEach(s => s.classList.toggle("active", s.getAttribute("data-value") <= val));
    };
  });
}'''

good_toggle = '''function toggleFeedbackForm(btn) {
  const form = btn.nextElementSibling;
  form.style.display = form.style.display === "none" ? "block" : "none";

  // Only attach star click events if we are in "Give Feedback" mode
  if (btn.textContent.trim() === "Give Feedback") {
      const stars = form.querySelectorAll(".feedback-star");
      stars.forEach(star => {
        star.onclick = () => {
          const val = star.getAttribute("data-value");
          stars.forEach(s => s.classList.toggle("active", s.getAttribute("data-value") <= val));
        };
      });
  }
}'''

if bad_toggle in text:
    text = text.replace(bad_toggle, good_toggle)
    with open('user/myorders.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Fixed toggleFeedbackForm logic")
else:
    print("Toggle logic not found")
