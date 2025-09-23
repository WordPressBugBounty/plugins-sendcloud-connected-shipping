document.addEventListener('DOMContentLoaded', () => {
    if (!window.wc?.blocksCheckout) return;

    const { extensionCartUpdate } = window.wc.blocksCheckout;
    let isUpdating = false;

    const overlay = createOverlay();
    document.body.appendChild(overlay);

    const showOverlay = () => overlay.style.display = 'block';
    const hideOverlay = () => overlay.style.display = 'none';

    const updateServicePoint = async (value) => {
        if (isUpdating) return;
        isUpdating = true;
        showOverlay();

        try {
            await extensionCartUpdate({
                namespace: 'sendcloud-connected-shipping-sp-block',
                data: { servicePoint: value },
            });

            wp.data.dispatch('wc/store/checkout').updateDraftOrder();

            console.log('Service Point updated:', value);
        } catch (err) {
            console.error('Error updating service point:', err);
        } finally {
            isUpdating = false;
            hideOverlay();
        }
    };

    const observeInput = (input) => {
        if (!input) return;

        updateServicePoint(input.value);

        input.addEventListener('input', () => updateServicePoint(input.value));
        input.addEventListener('change', () => updateServicePoint(input.value));

        new MutationObserver(() => updateServicePoint(input.value))
            .observe(input, { attributes: true, attributeFilter: ['value'] });
    };

    const observer = new MutationObserver((mutationsList) => {
        for (const mutation of mutationsList) {
            mutation.addedNodes.forEach(node => {
                if (node.nodeType !== 1) return;

                const input = node.querySelector('#sendcloudshipping_service_point_extra_v2') ||
                    (node.id === 'sendcloudshipping_service_point_extra_v2' ? node : null);
                if (input) observeInput(input);
            });
        }
    });

    observer.observe(document.body, { childList: true, subtree: true });

    // Clear session value on page load
    updateServicePoint(null);

    function createOverlay() {
        const div = document.createElement('div');
        Object.assign(div.style, {
            position: 'fixed',
            top: '0',
            left: '0',
            width: '100%',
            height: '100%',
            backgroundColor: 'rgba(0, 0, 0, 0.3)',
            zIndex: '9999',
            display: 'none'
        });
        return div;
    }
});
