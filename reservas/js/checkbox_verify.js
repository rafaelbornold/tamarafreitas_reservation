(function One() {
    const form = document.querySelector('#divDisponibilidad');
    const checkboxes = form.querySelectorAll('input[type=checkbox]');
    const checkboxLength = checkboxes.length;
    const firstCheckbox = checkboxLength > 0 ? checkboxes[0] : null;

    function init() {
        if (firstCheckbox) {
            for (let i = 0; i < checkboxLength; i++) {
                checkboxes[i].addEventListener('change', checkValidity);
            }

            checkValidity();
        }
    }

    function isChecked() {
        for (let i = 0; i < checkboxLength; i++) {
            if (checkboxes[i].checked) return true;
        }

        return false;
    }

    function checkValidity() {
        const errorMessage = !isChecked() ? 'Debe seleccionarse al menos una casilla de verificación.' : '';
        firstCheckbox.setCustomValidity(errorMessage);
    }

    init();
})();

(function Two() {
    const div = document.querySelector('#divProcedimientos');
    const checkboxes1 = div.querySelectorAll('input[type=checkbox]');
    const checkboxLength1 = checkboxes1.length;
    const firstCheckbox1 = checkboxLength1 > 0 ? checkboxes1[0] : null;

    function initTwo() {
        if (firstCheckbox1) {
            for (let i = 0; i < checkboxLength1; i++) {
                checkboxes1[i].addEventListener('change', checkValidityTwo);
            }

            checkValidityTwo();
        }
    }

    function isCheckedTwo() {
        for (let i = 0; i < checkboxLength1; i++) {
            if (checkboxes1[i].checked) return true;
        }

        return false;
    }

    function checkValidityTwo() {
        const errorMessage = !isCheckedTwo() ? 'Debe seleccionarse al menos una casilla de verificación.' : '';
        firstCheckbox1.setCustomValidity(errorMessage);
    }

    initTwo();
})();
