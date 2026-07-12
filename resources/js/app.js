document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('login-modal');
    if (!modal) return;

    const redirectField = document.getElementById('login-redirect-to');
    const closeButtons = modal.querySelectorAll('[data-modal-close]');

    const openModal = (topicId, optionId) => {
        if (redirectField) {
            // Remembers which option the guest was trying to pick so the
            // controller can re-select it (or cast the vote outright)
            // once login succeeds.
            redirectField.value = `topic-${topicId}-option-${optionId}`;
        }
        modal.showModal();
    };

    const closeModal = () => modal.close();

    // Every vote option is a <label> wrapping a hidden radio input.
    // Guests never actually select the radio — touching the option
    // always opens the login modal first.
    document.querySelectorAll('[data-vote-option]').forEach((option) => {
        option.addEventListener('click', (event) => {
            event.preventDefault();
            openModal(option.dataset.topicId, option.dataset.optionId);
        });
    });

    closeButtons.forEach((btn) => btn.addEventListener('click', closeModal));

    // Click on the ::backdrop closes the dialog too
    modal.addEventListener('click', (event) => {
        if (event.target === modal) closeModal();
    });
});
