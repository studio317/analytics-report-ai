(function () {
	'use strict';

	function initializeScopeField() {
		var scopeInputs = document.querySelectorAll('[data-analytics-report-ai-scope]');
		var pathField = document.querySelector('[data-analytics-report-ai-path-field]');
		var pathInput = document.querySelector('[data-analytics-report-ai-path-input]');
		var pathDescription = document.querySelector('[data-analytics-report-ai-path-description]');

		if (!scopeInputs.length || !pathField || !pathInput || !pathDescription) {
			return;
		}

		function getSelectedScope() {
			var selected = document.querySelector('[data-analytics-report-ai-scope]:checked');

			return selected ? selected.value : 'site';
		}

		function updatePathField() {
			var scope = getSelectedScope();

			if ('site' === scope) {
				pathField.classList.add('is-hidden');
				pathInput.value = '';
				pathInput.setAttribute('disabled', 'disabled');
				return;
			}

			pathField.classList.remove('is-hidden');
			pathInput.removeAttribute('disabled');

			if ('directory' === scope) {
				pathInput.setAttribute('placeholder', '/blog/');
				pathDescription.textContent = 'For directory scope, enter a path such as /blog/. It will be normalized before payload creation.';
				return;
			}

			if ('page' === scope) {
				pathInput.setAttribute('placeholder', '/about');
				pathDescription.textContent = 'For page scope, enter a path such as /about or /about/. It will be normalized before payload creation.';
			}
		}

		Array.prototype.forEach.call(scopeInputs, function (input) {
			input.addEventListener('change', updatePathField);
		});

		updatePathField();
	}

	function initializeCopyReport() {
		var copyButton = document.querySelector('[data-analytics-report-ai-copy-report]');
		var textarea = document.querySelector('[data-analytics-report-ai-report-textarea]');
		var status = document.querySelector('[data-analytics-report-ai-copy-status]');

		if (!copyButton || !textarea) {
			return;
		}

		function setStatus(message) {
			if (!status) {
				return;
			}

			status.textContent = message;

			window.setTimeout(function () {
				status.textContent = '';
			}, 3000);
		}

		function fallbackCopy(text) {
			textarea.focus();
			textarea.select();

			try {
				document.execCommand('copy');
				setStatus('Copied.');
			} catch (error) {
				setStatus('Copy failed. Please select and copy manually.');
			}
		}

		copyButton.addEventListener('click', function () {
			var text = textarea.value;

			if (!text) {
				setStatus('Nothing to copy.');
				return;
			}

			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(text)
					.then(function () {
						setStatus('Copied.');
					})
					.catch(function () {
						fallbackCopy(text);
					});

				return;
			}

			fallbackCopy(text);
		});
	}

	function initializeConfirmButtons() {
		var buttons = document.querySelectorAll('[data-analytics-report-ai-confirm]');

		if (!buttons.length) {
			return;
		}

		Array.prototype.forEach.call(buttons, function (button) {
			button.addEventListener('click', function (event) {
				var message = button.getAttribute('data-analytics-report-ai-confirm');

				if (message && !window.confirm(message)) {
					event.preventDefault();
				}
			});
		});
	}

	function initializeSingleSubmitForms() {
		var forms = document.querySelectorAll('[data-analytics-report-ai-single-submit]');

		if (!forms.length) {
			return;
		}

		Array.prototype.forEach.call(forms, function (form) {
			form.addEventListener('submit', function (event) {
				var button = form.querySelector('[data-analytics-report-ai-submit-button]');

				if (form.getAttribute('data-analytics-report-ai-submitting')) {
					event.preventDefault();
					return;
				}

				form.setAttribute('data-analytics-report-ai-submitting', '1');

				if (button) {
					button.setAttribute('disabled', 'disabled');
				}
			});
		});
	}

	function initialize() {
		initializeScopeField();
		initializeCopyReport();
		initializeConfirmButtons();
		initializeSingleSubmitForms();
	}

	if ('loading' === document.readyState) {
		document.addEventListener('DOMContentLoaded', initialize);
	} else {
		initialize();
	}
})();
