var FurtexUtil = {
	// Track created dialogues and scripts for each page
	_dialogues: {},
	_scripts: {},
	
	createPopup: function(title, okLabel, cancelLabel, components, method="post", action="") {
	  const dialog = document.createElement("dialog");
	  dialog.classList.add('furtex-dialog');
	  
	  // Store the dialog in the current page's dialogues
	  const pageId = this.pageId;
	  if (!this._dialogues[pageId]) this._dialogues[pageId] = [];
	  this._dialogues[pageId].push(dialog);
	  
	  // Register cleanup for this dialog
	  this.registerCleanup(() => {
		if (dialog && dialog.parentNode) {
		  dialog.parentNode.removeChild(dialog);
		}
	  });

	  const header = document.createElement("h3");
	  header.textContent = title;

	  const formdiv = document.createElement("form");
	  formdiv.method = method;
	  formdiv.action = action;

	  formdiv.appendChild(header);

	  // Create components
	  const inputs = {};
	  for (const part of components) {
		const [type, label, extra, name] = part;
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
		  input.type = extra;
		  input.name = name;
		  input.id = name;
		  inputs[label] = input;

		  wrapper.appendChild(input);
		  formdiv.appendChild(wrapper);
		} else if (type === "checkbox") { // --------------------------------- CHECKBOX
		  const wrapper = document.createElement("label");
		  wrapper.style.display = "flex";
		  wrapper.style.alignItems = "center";
		  wrapper.style.gap = "0.5em";

		  const check = document.createElement("input");
		  check.type = "checkbox";
		  check.name = name;
		  check.id = name;
		  inputs[label] = check;

		  wrapper.appendChild(check);
		  wrapper.append(label);
		  formdiv.appendChild(wrapper);
		} else if (type === "dropdown") { // -------------------------------- DROPDOWN
			const wrapper = document.createElement("label");
			wrapper.textContent = label;
			wrapper.style.display = "flex";
			wrapper.style.flexDirection = "column";

			const select = document.createElement("select");
			select.name = name;
			select.id = name;
			inputs[label] = select;

			if (Array.isArray(extra)) {
				extra.forEach(opt => {
				  const option = document.createElement("option");
				  if (typeof opt === "object") {
					// { value: 'val', text: 'Display' }
					option.value = opt.value;
					option.textContent = opt.text;
				  } else {
					// "OptionText"
					option.value = opt;
					option.textContent = opt;
				  }
				  select.appendChild(option);
				});
			}

			wrapper.appendChild(select);
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
	  cancelBtn.class = "btn";
	  cancelBtn.onclick = () => dialog.close();

	  const okBtn = document.createElement("button");
	  okBtn.textContent = okLabel;
	  okBtn.type = "submit";
	  okBtn.name = "submit";
	  okBtn.class = "btn";
	  okBtn.ids = "submit";

	  buttonRow.append(cancelBtn, okBtn);
	  formdiv.appendChild(buttonRow);

	  dialog.appendChild(formdiv);
	  document.body.appendChild(dialog);

	  return dialog; // you can set dialog.onSubmit = (data) => {...}
	},

	createTablesFromRow: function(rows) {
	  const table = document.createElement("table");

	  if (!Array.isArray(rows) || rows.length === 0) {
		const empty = document.createElement("caption");
		empty.textContent = "No data available :<";
		table.appendChild(empty);
		return table;
	  }

	  // Create thead
	  const thead = document.createElement("thead");
	  const headRow = document.createElement("tr");

	  for (const key of Object.keys(rows[0])) {
		const th = document.createElement("th");
		th.textContent = key;
		headRow.appendChild(th);
	  }

	  thead.appendChild(headRow);
	  table.appendChild(thead);

	  // Create tbody
	  const tbody = document.createElement("tbody");

	  for (const row of rows) {
		const tr = document.createElement("tr");

		for (const value of Object.values(row)) {
		  const td = document.createElement("td");
		  td.textContent = value;
		  tr.appendChild(td);
		}

		tbody.appendChild(tr);
	  }

	  table.appendChild(tbody);

	  return table;
	},

	showAnimated: function(dialog) {
		if (!dialog.open) {
			dialog.showModal();
		}
		dialog.classList.remove('closing');
	},

	hideAnimated: function(dialog) {
		dialog.classList.add('closing');
		setTimeout(() => {
			dialog.close();
		}, 200); // match your CSS transition duration!
	},
	
	createPageDialogue: function(title, content, buttons = []) {
		const dialog = document.createElement("dialog");
		dialog.classList.add('furtex-dialog', 'page-dialogue');
		
		// Store the dialog in the current page's dialogues
		const pageId = this.pageId;
		if (!this._dialogues[pageId]) this._dialogues[pageId] = [];
		this._dialogues[pageId].push(dialog);
		
		// Create dialogue content
		const header = document.createElement("h3");
		header.textContent = title;
		
		const contentDiv = document.createElement("div");
		contentDiv.classList.add('dialogue-content');
		contentDiv.innerHTML = content;
		
		// Create buttons
		const buttonRow = document.createElement("div");
		buttonRow.style.display = "flex";
		buttonRow.style.justifyContent = "flex-end";
		buttonRow.style.gap = "0.5em";
		buttonRow.style.marginTop = "1em";
		
		if (buttons.length === 0) {
			// Default close button
			const closeBtn = document.createElement("button");
			closeBtn.textContent = "Close";
			closeBtn.type = "button";
			closeBtn.classList.add("btn");
			closeBtn.onclick = () => dialog.close();
			buttonRow.appendChild(closeBtn);
		} else {
			// Custom buttons
			buttons.forEach(btn => {
				const button = document.createElement("button");
				button.textContent = btn.text;
				button.type = "button";
				button.classList.add("btn");
				if (btn.action) {
					button.onclick = () => {
						btn.action();
						if (btn.closeOnClick !== false) {
							dialog.close();
						}
					};
				} else {
					button.onclick = () => dialog.close();
				}
				buttonRow.appendChild(button);
			});
		}
		
		dialog.appendChild(header);
		dialog.appendChild(contentDiv);
		dialog.appendChild(buttonRow);
		document.body.appendChild(dialog);
		
		// Register cleanup for this dialog
		this.registerCleanup(() => {
			if (dialog && dialog.parentNode) {
				dialog.parentNode.removeChild(dialog);
			}
		});
		
		return dialog;
	},
	
	editPopup: function(dialog, values) {
	  // values = { inputName: newValue, anotherName: newValue, ... }
	  const form = dialog.querySelector("form");
	  if (!form) return;

	  Object.entries(values).forEach(([name, newValue]) => {
		const el = form.querySelector(`[name="${name}"]`);
		if (!el) return;

		if (el.type === "checkbox") {
		  el.checked = Boolean(newValue);
		} else if (el.tagName === "SELECT") {
		  el.value = newValue;
		} else {
		  el.value = newValue;
		}
	  });
	},
	
	// page instance id (increments on each navigation)
	pageId: 1,
	// internal map: pageId -> [cleanupFns]
	_cleanups: {},

	// CLEANUP SYSTEM (per-page)
	registerCleanup: function(fn, pageId = null) {
		const id = pageId === null ? this.pageId : pageId;
		if (!this._cleanups[id]) this._cleanups[id] = [];
		this._cleanups[id].push(fn);
		// return unregister function
		return () => {
			if (!this._cleanups[id]) return;
			this._cleanups[id] = this._cleanups[id].filter(x => x !== fn);
		};
	},

	// Track scripts added to the page
	trackScript: function(script) {
		const pageId = this.pageId;
		if (!this._scripts[pageId]) this._scripts[pageId] = [];
		this._scripts[pageId].push(script);
		return script;
	},
	
	runCleanupsFor: function(pageId) {
		// Clean up dialogues for this page
		if (this._dialogues[pageId]) {
			this._dialogues[pageId].forEach(dialog => {
				try {
					if (dialog && dialog.parentNode) {
						dialog.parentNode.removeChild(dialog);
					}
				} catch(e) {
					console.warn("dialogue cleanup failed", e);
				}
			});
			delete this._dialogues[pageId];
		}
		
		// Clean up scripts for this page
		if (this._scripts[pageId]) {
			this._scripts[pageId].forEach(script => {
				try {
					if (script && script.parentNode) {
						script.parentNode.removeChild(script);
					}
				} catch(e) {
					console.warn("script cleanup failed", e);
				}
			});
			delete this._scripts[pageId];
		}
		
		// Run registered cleanup functions
		if (!this._cleanups[pageId]) return;
		try {
			this._cleanups[pageId].forEach(fn => {
				try { fn(); } catch(e){ console.warn("cleanup failed", e); }
			});
		} finally {
			delete this._cleanups[pageId];
		}
  }
}