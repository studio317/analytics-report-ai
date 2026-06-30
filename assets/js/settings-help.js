(function () {
	'use strict';

	function getDialog(button) {
		var dialogId = button.getAttribute('data-analytics-report-ai-dialog-target');

		if (!dialogId) {
			return null;
		}

		return document.getElementById(dialogId);
	}

	function closeDialog(dialog, returnFocus) {
		var openerId = dialog.getAttribute('data-analytics-report-ai-opener');
		var opener = openerId ? document.getElementById(openerId) : null;

		dialog.hidden = true;

		if (opener) {
			opener.setAttribute('aria-expanded', 'false');

			if (returnFocus) {
				opener.focus();
			}
		}

		dialog.removeAttribute('data-analytics-report-ai-opener');
	}

	function openDialog(button, dialog) {
		var focusTarget = dialog.querySelector('[data-analytics-report-ai-help-close]') || dialog.querySelector('[role="dialog"]');

		if (!button.id) {
			button.id = 'analytics-report-ai-help-button-' + Math.random().toString(36).slice(2);
		}

		dialog.setAttribute('data-analytics-report-ai-opener', button.id);
		dialog.hidden = false;
		button.setAttribute('aria-expanded', 'true');

		if (focusTarget) {
			focusTarget.focus();
		}
	}

	function initializeHelpDialogs() {
		var buttons = document.querySelectorAll('[data-analytics-report-ai-help-button]');
		var dialogs = document.querySelectorAll('[data-analytics-report-ai-help-dialog]');

		if (!buttons.length || !dialogs.length) {
			return;
		}

		Array.prototype.forEach.call(buttons, function (button) {
			button.addEventListener('click', function () {
				var dialog = getDialog(button);

				if (!dialog) {
					return;
				}

				if (dialog.hidden) {
					openDialog(button, dialog);
					return;
				}

				closeDialog(dialog, true);
			});
		});

		Array.prototype.forEach.call(dialogs, function (dialog) {
			var closeButton = dialog.querySelector('[data-analytics-report-ai-help-close]');

			if (closeButton) {
				closeButton.addEventListener('click', function () {
					closeDialog(dialog, true);
				});
			}

			dialog.addEventListener('keydown', function (event) {
				if ('Escape' === event.key) {
					event.preventDefault();
					closeDialog(dialog, true);
				}
			});
		});
	}

	if ('loading' === document.readyState) {
		document.addEventListener('DOMContentLoaded', initializeHelpDialogs);
	} else {
		initializeHelpDialogs();
	}
})();
