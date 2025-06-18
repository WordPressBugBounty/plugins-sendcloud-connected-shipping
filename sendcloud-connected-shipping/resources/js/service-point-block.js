document.addEventListener('DOMContentLoaded', function () {
    // Check if block checkout is being used
    if (window.wc && window.wc.blocksCheckout) {
        // Create a MutationObserver to detect when the hidden input is added
        const observer = new MutationObserver((mutationsList, observer) => {
            for (const mutation of mutationsList) {
                for (const node of mutation.addedNodes) {
                    if (node.nodeType === 1) { // Ensure it's an element
                        const hiddenInput = node.querySelector?.('#sendcloudshipping_service_point_extra_v2') ||
                            (node.id === 'sendcloudshipping_service_point_extra_v2' ? node : null);

                        if (hiddenInput) {
                            // Get the value from the hidden input
                            const servicePoint = hiddenInput.value;

                            // Dispatch the data to WooCommerce
                            wp.data.dispatch('wc/store/checkout').__internalSetExtensionData(
                                'sendcloud-connected-shipping',
                                {
                                    service_point: servicePoint,
                                }
                            );

                            // Stop observing after the element is found
                            observer.disconnect();
                        }
                    }
                }
            }
        });

        // Start observing the document body for added nodes
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
});
