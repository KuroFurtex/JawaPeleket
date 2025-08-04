function createPopup(title, okLabel, cancelLabel, components, method="post", action="") {
  const dialog = document.createElement("dialog");

  const header = document.createElement("h3");
  header.textContent = title;

  const formdiv = document.createElement("form");
  formdiv.method = method;
  formdiv.action = action;

  formdiv.appendChild(header);

  // Create components
  const inputs = {};
  for (const part of components) {
    const [type, label, extra] = part;
    if (type === "line") { // --------------------------------- LINE
      const p = document.createElement("p");
      p.textContent = label;
      formdiv.appendChild(p);
    } else if (type === "textbox") { // --------------------------------- TEXTBOX
      const wrapper = document.createElement("label");
      wrapper.textContent = label;
      wrapper.style.display = "flex";
      wrapper.style.flexDirection = "column";

      const input = document.createElement("input");
      input.type = extra === "Pass" ? "password" : "text";
      inputs[label] = input;

      wrapper.appendChild(input);
      formdiv.appendChild(wrapper);
    } else if (type === "checkbox") { // --------------------------------- CHECKBOX
      const wrapper = document.createElement("label");
      wrapper.style.display = "flex";
      wrapper.style.alignItems = "center";
      wrapper.style.gap = "0.5em";

      const checkbox = document.createElement("input");
      checkbox.type = "checkbox";
      inputs[label] = checkbox;

      wrapper.appendChild(checkbox);
      wrapper.append(label);
      formdiv.appendChild(wrapper);
    }
  }

  // Buttons
  const buttonRow = document.createElement("div");
  buttonRow.style.display = "flex";
  buttonRow.style.justifyContent = "flex-end";
  buttonRow.style.gap = "0.5em";
  buttonRow.style.marginTop = "1em";

  const cancelBtn = document.createElement("button");
  cancelBtn.textContent = cancelLabel;
  cancelBtn.type = "button";
  cancelBtn.onclick = () => dialog.close();

  const okBtn = document.createElement("button");
  okBtn.textContent = okLabel;
  okBtn.type = "submit";

  buttonRow.append(cancelBtn, okBtn);
  formdiv.appendChild(buttonRow);

  formdiv.onsubmit = (e) => {
    e.preventDefault();
    dialog.close("ok");
    if (dialog.onSubmit) {
      // collect values
      const result = {};
      for (const key in inputs) {
        const el = inputs[key];
        result[key] = el.type === "checkbox" ? el.checked : el.value;
      }
      dialog.onSubmit(result);
    }
  };

  dialog.appendChild(formdiv);
  document.body.appendChild(dialog);

  return dialog; // you can set dialog.onSubmit = (data) => {...}
}
