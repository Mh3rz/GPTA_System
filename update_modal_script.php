<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('insertForm'); // Add an ID to your form

        form.addEventListener('submit', function (event) {
            // Validate the form before submission
            if (!validateForm()) {
                event.preventDefault(); // Prevent the form from being submitted
            }
        });

        function validateForm() {
            const requiredFields = ['Lastname', 'Firstname', 'Middle_Initial'];

            let isValid = true;

            requiredFields.forEach(field => {
                const input = document.getElementById(field);
                if (field === 'Middle_Initial' && input.value.trim() === '') {
                    // Middle initial is optional, so no validation needed if it's empty
                    return;
                }

                if (input.value.trim() === '') {
                    isValid = false;
                    alert(`Please fill in the ${field.replace('_', ' ')}.`);
                } else if (field === 'Middle_Initial' && !isValidMiddleInitial(input.value.trim())) {
                    isValid = false;
                    alert(`Invalid format in Middle Initial. It should be a single uppercase letter followed by a period and should not contain numbers.`);
                } else if ((field === 'Firstname' || field === 'Lastname') && !isValidName(input.value)) {
                    isValid = false;
                    alert(`Invalid characters in ${field.replace('_', ' ')}. Only letters and spaces are allowed.`);
                }
            });

            return isValid;
        }

        function isValidName(name) {
            // Regular expression to allow only letters (uppercase and lowercase) and spaces
            const nameRegex = /^[A-Za-z\s]+$/;
            return nameRegex.test(name);
        }

        function isValidMiddleInitial(m_i) {
            // Regular expression to allow a single uppercase letter followed by a period and no numbers
            const m_iRegex = /^[A-Z]\.$/;
            return m_iRegex.test(m_i);
        }
    });
</script>
