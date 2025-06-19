document.addEventListener('DOMContentLoaded', function() {
    // Initialize form validation
    initializeFormValidation();
    initializeModals();
});

function initializeFormValidation() {
    // Deposit form validation
    const depositForm = document.getElementById("depositForm");
    if (depositForm) {
        depositForm.addEventListener("submit", function (e) {
            const amount = parseFloat(document.getElementById("depositAmount").value);
            const dompetSelect = document.getElementById("dompet_id_deposit");
            
            if (isNaN(amount) || amount < 1) {
                e.preventDefault();
                alert("Jumlah deposit harus lebih dari atau sama dengan Rp1.");
                return;
            }

            if (!dompetSelect.value) {
                e.preventDefault();
                alert("Silakan pilih dompet terlebih dahulu.");
                return;
            }
        });
    }

    // Withdraw form validation
    const withdrawForm = document.getElementById("withdrawForm");
    if (withdrawForm) {
        withdrawForm.addEventListener("submit", function (e) {
            const amount = parseFloat(document.getElementById("withdrawAmount").value);
            const dompetSelect = document.getElementById("dompet_id_withdraw");
            
            if (isNaN(amount) || amount < 1) {
                e.preventDefault();
                alert("Jumlah withdraw harus lebih dari atau sama dengan Rp1.");
                return;
            }

            if (!dompetSelect.value) {
                e.preventDefault();
                alert("Silakan pilih dompet terlebih dahulu.");
                return;
            }

            const selectedOption = dompetSelect.selectedOptions[0];
            const saldo = parseFloat(selectedOption.getAttribute("data-saldo"));

            if (amount > saldo) {
                e.preventDefault();
                alert("Saldo tidak mencukupi untuk withdraw.");
                return;
            }
        });
    }
}

function initializeModals() {
    // Initialize Bootstrap modals
    const depositModal = new bootstrap.Modal(
        document.getElementById("depositModal")
    );
    const withdrawModal = new bootstrap.Modal(
        document.getElementById("withdrawModal")
    );

    // Handle deposit form submission
    const depositForm = document.getElementById("depositForm");
    const dompetSelectDeposit = document.getElementById("dompet_id_deposit");

    if (depositForm && dompetSelectDeposit) {
        depositForm.addEventListener("submit", function (e) {
            const formData = new FormData(depositForm);
            depositForm.action =
                "{{ route('dompet.deposit', '__id__') }}".replace(
                    "__id__",
                    dompetSelectDeposit.value
                );

            // Remove the dompet_id field since it's passed in the URL
            formData.delete("dompet_id");
            formData.delete("tipe");
        });
    }
}

    // Handle withdraw form submission
    const withdrawForm = document.getElementById("withdrawForm");
    const dompetSelectWithdraw = document.getElementById("dompet_id_withdraw");

    if (withdrawForm && dompetSelectWithdraw) {
        withdrawForm.addEventListener("submit", function (e) {
            // Get the form data
            const formData = new FormData(withdrawForm);

            // Set the action URL with the selected dompet ID
            withdrawForm.action =
                "{{ route('dompet.withdraw', '__id__') }}".replace(
                    "__id__",
                    dompetSelectWithdraw.value
                )

            formData.delete("dompet_id");
            formData.delete("tipe");
        })
    }
});
