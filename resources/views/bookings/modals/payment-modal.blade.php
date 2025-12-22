<!-- Modal de Pagamento -->
<div id="paymentModal" class="hidden fixed inset-0 backdrop-blur-sm z-[9998] backdrop-brightness-50 flex items-center justify-center z-50">
    <div class="text-base bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 z-[9999]">
        <!-- Header -->
        <div class="p-4 border-b border-gray-200 bg-gray-600 text-white text-xl font-bold  rounded-t-2xl">
            <h2>Confirm payment</h2>
        </div>

        <!-- Body -->
        <div class="p-6">
            <!-- Payment Details (shown in edit mode) -->
            <div id="paymentDetails" class="hidden mb-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                <!-- Will be populated by JS -->
            </div>

            <h3 class="text-lg font-semibold text-gray-900 mb-4">Choose a payment method</h3>

            <!-- Métodos de Pagamento -->
            <div class="flex gap-3 mb-6">
                <!-- Debit/Credit Card -->
                <button type="button" onclick="selectPaymentMethod('card')"
                        class="payment-method flex-2 px-3 py-2 border-2 border-gray-300 rounded-xl hover:border-blue-600 hover:bg-blue-50 transition flex items-center justify-center gap-2"
                        data-method="card">
                    <!-- Visa Logo -->
                    <svg class="h-5" viewBox="0 0 48 32" fill="none">
                        <rect width="48" height="32" rx="4" fill="#1A1F71"/>
                        <path d="M19.8 21.5L21.9 10.5H24.8L22.7 21.5H19.8Z" fill="white"/>
                        <path d="M33.2 10.8C32.7 10.6 31.9 10.4 30.9 10.4C28 10.4 26 11.9 26 14.1C26 15.7 27.5 16.5 28.6 17C29.8 17.5 30.2 17.8 30.2 18.3C30.2 19.1 29.2 19.4 28.3 19.4C27 19.4 26.3 19.2 25.3 18.8L24.9 18.6L24.5 21C25.1 21.3 26.2 21.5 27.4 21.5C30.5 21.5 32.4 20.1 32.5 17.7C32.5 16.5 31.7 15.6 30 14.9C29 14.4 28.4 14.1 28.4 13.6C28.4 13.1 28.9 12.6 30.1 12.6C31 12.6 31.6 12.8 32.1 13L32.4 13.1L32.8 10.8H33.2Z" fill="white"/>
                        <path d="M37.8 10.5H35.6C34.9 10.5 34.4 10.7 34.1 11.4L29.7 21.5H32.8L33.4 19.9H37.1L37.4 21.5H40.1L37.8 10.5ZM34.3 17.7L35.7 13.8L36.5 17.7H34.3Z" fill="white"/>
                        <path d="M17.8 10.5L15 19.1L14.7 17.7C14.2 16 12.5 14.1 10.6 13.1L13.2 21.5H16.3L21.1 10.5H17.8Z" fill="white"/>
                        <path d="M12.3 10.5H8L8 10.7C11.6 11.6 14 13.8 14.7 16.8L13.9 11.4C13.8 10.8 13.3 10.5 12.3 10.5Z" fill="#F7B600"/>
                    </svg>

                    <!-- Mastercard Logo -->
                    <svg class="h-5" viewBox="0 0 48 32" fill="none">
                        <rect width="48" height="32" rx="4" fill="#000000"/>
                        <circle cx="18" cy="16" r="8" fill="#EB001B"/>
                        <circle cx="30" cy="16" r="8" fill="#F79E1B"/>
                        <path d="M24 9.5C22.4 10.8 21.3 12.8 21.3 15C21.3 17.2 22.4 19.2 24 20.5C25.6 19.2 26.7 17.2 26.7 15C26.7 12.8 25.6 10.8 24 9.5Z" fill="#FF5F00"/>
                    </svg>

                    <!-- American Express Logo -->
                    <img src="{{ asset('images/american-express.png') }}"
                         alt="PayPal"
                         class="h-6">
                </button>

                <!-- MB Way -->
                <button type="button" onclick="selectPaymentMethod('mbway')"
                        class="flex-1 payment-method px-3 py-2 border-2 border-gray-300 rounded-xl hover:border-red-600 hover:bg-red-50 transition flex items-center justify-center gap-2"
                        data-method="mbway">
                    <svg class="h-6" xmlns="http://www.w3.org/2000/svg" id="Camada_1" data-name="Camada 1" viewBox="0 0 143.2 69.57"><defs><style>.cls-1{fill:red;}.cls-2{fill:#1d1d1b;}</style></defs><title>Logo_MBWay</title><path class="cls-1" d="M7.07,61.84l-.24,1.88a1.54,1.54,0,0,0,1.35,1.72H69.29a1.56,1.56,0,0,0,1.58-1.54,1.15,1.15,0,0,0,0-.19l-.25-1.88A2.68,2.68,0,0,1,73,58.9a2.64,2.64,0,0,1,2.91,2.34v0l.24,1.83c.47,4.07-1.84,7.65-6,7.65H7.51c-4.12,0-6.42-3.58-5.95-7.65l.24-1.83A2.62,2.62,0,0,1,4.68,58.9h0a2.69,2.69,0,0,1,2.38,2.94" transform="translate(-1.5 -1.16)"/><path class="cls-2" d="M63.37,47.71A5,5,0,0,0,68.63,43a2.35,2.35,0,0,0,0-.26c-.06-2.91-2.71-4.79-5.66-4.8H57a2.48,2.48,0,0,1,0-5h4c2.69-.11,4.76-1.74,4.89-4.27.13-2.73-2.21-4.77-5.06-4.77H51.15l0,23.77H63.37m7.33-19a7.84,7.84,0,0,1-2.33,5.61l-.15.17.2.12a9.74,9.74,0,0,1,5,8.14,10,10,0,0,1-9.8,10.13h-15a2.63,2.63,0,0,1-2.59-2.65h0V21.66A2.62,2.62,0,0,1,48.68,19h0l12.15,0a9.61,9.61,0,0,1,9.87,9.33v.33" transform="translate(-1.5 -1.16)"/><path class="cls-2" d="M23.26,43.08l.07.2.07-.2c.68-1.88,1.51-4,2.38-6.23s1.8-4.67,2.69-6.85,1.76-4.18,2.58-5.9a19.91,19.91,0,0,1,2-3.61A4,4,0,0,1,36.26,19h.61a2.91,2.91,0,0,1,1.92.62A2.15,2.15,0,0,1,39.55,21l3.81,29.5a2.47,2.47,0,0,1-.65,1.79,2.6,2.6,0,0,1-1.85.6,3,3,0,0,1-1.92-.56,2.07,2.07,0,0,1-.89-1.48c-.13-1-.24-2.07-.36-3.27s-.76-6.33-.93-7.64-1.22-9.66-1.59-12.69l0-.26-1.22,2.56c-.41.88-.86,1.93-1.35,3.16s-1,2.53-1.47,3.91-2.89,8.06-2.89,8.06c-.22.61-.64,1.84-1,3s-.73,2.15-.82,2.34a3.42,3.42,0,0,1-4.6,1.49A3.46,3.46,0,0,1,20.29,50c-.1-.19-.44-1.21-.83-2.34s-.77-2.35-1-3c0,0-2.35-6.74-2.88-8.06s-1-2.67-1.47-3.91-.95-2.28-1.35-3.16L11.53,27l0,.26c-.37,3-1.43,11.36-1.6,12.69S9.14,46.36,9,47.55s-.25,2.29-.37,3.27a2.07,2.07,0,0,1-.89,1.48,3,3,0,0,1-1.91.56A2.57,2.57,0,0,1,4,52.26a2.47,2.47,0,0,1-.65-1.79L7.11,21a2.16,2.16,0,0,1,.77-1.32A2.88,2.88,0,0,1,9.8,19h.61a4,4,0,0,1,3.19,1.46,19.33,19.33,0,0,1,2,3.61q1.23,2.58,2.58,5.9t2.7,6.85c.87,2.26,1.69,4.35,2.37,6.23" transform="translate(-1.5 -1.16)"/><path class="cls-1" d="M15.8,1.16H62.06c4.36,0,6.53,3.27,7,7.59l.2,1.38a2.72,2.72,0,0,1-2.39,3A2.67,2.67,0,0,1,64,10.71v0L63.8,9.38c-.19-1.64-.88-2.91-2.55-2.91H16.62c-1.67,0-2.36,1.27-2.56,2.91l-.18,1.31A2.66,2.66,0,0,1,11,13.1h0a2.71,2.71,0,0,1-2.39-3l.19-1.38c.52-4.31,2.68-7.59,7-7.59" transform="translate(-1.5 -1.16)"/><path class="cls-2" d="M99,32.26c-.32,1.23-.65,2.55-1,4s-.7,2.75-1,4-.65,2.39-1,3.36a10.89,10.89,0,0,1-.76,2,2,2,0,0,1-1.89.94,4.09,4.09,0,0,1-1-.15,1.63,1.63,0,0,1-1-.86,12.06,12.06,0,0,1-.76-2.08c-.3-1-.62-2.22-1-3.57s-.67-2.77-1-4.28-.65-2.91-.91-4.2-.5-2.4-.68-3.3-.28-1.45-.31-1.64a1.6,1.6,0,0,1,0-.23v-.13a1.13,1.13,0,0,1,.44-.93,1.63,1.63,0,0,1,1.08-.35,1.76,1.76,0,0,1,1,.26,1.39,1.39,0,0,1,.54.89s.06.37.18,1,.29,1.38.48,2.31.41,2,.64,3.17.48,2.36.75,3.56.52,2.35.78,3.48.49,2.09.72,2.9c.22-.76.47-1.63.74-2.61s.55-2,.82-3,.52-2.09.77-3.13.48-2,.7-2.92.39-1.69.55-2.39.28-1.21.37-1.55a1.9,1.9,0,0,1,.64-1A1.78,1.78,0,0,1,99,25.35a1.84,1.84,0,0,1,1.22.39,1.71,1.71,0,0,1,.6,1c.27,1.09.53,2.33.82,3.69s.6,2.73.91,4.12.65,2.76,1,4.1.67,2.52,1,3.55c.22-.81.47-1.77.73-2.89s.51-2.28.78-3.48.54-2.36.78-3.53.48-2.22.68-3.15.37-1.69.48-2.27.19-.9.19-.92a1.49,1.49,0,0,1,.54-.88,1.72,1.72,0,0,1,1-.26,1.69,1.69,0,0,1,1.09.35,1.16,1.16,0,0,1,.44.93v.13a2,2,0,0,1,0,.24c0,.18-.13.72-.32,1.64s-.42,2-.69,3.29-.58,2.69-.91,4.18-.68,2.91-1,4.26-.64,2.54-1,3.56a11.57,11.57,0,0,1-.76,2.06,1.77,1.77,0,0,1-1,.9,3.45,3.45,0,0,1-1,.18,2.83,2.83,0,0,1-.41,0,3.75,3.75,0,0,1-.58-.13,2.31,2.31,0,0,1-.6-.32,1.49,1.49,0,0,1-.48-.6,15.11,15.11,0,0,1-.72-2.12c-.29-1-.59-2.1-.92-3.34s-.64-2.56-1-3.92-.61-2.63-.88-3.81" transform="translate(-1.5 -1.16)"/><path class="cls-2" d="M116.69,40.3c-.34,1.08-.64,2.08-.89,3s-.51,1.67-.73,2.26a1.51,1.51,0,0,1-3-.4,1.31,1.31,0,0,1,.07-.44l.42-1.39c.24-.78.55-1.75.93-2.93s.81-2.44,1.27-3.83.94-2.77,1.43-4.13,1-2.63,1.46-3.8A23.07,23.07,0,0,1,119,25.78a1.56,1.56,0,0,1,.73-.77,3.11,3.11,0,0,1,1.24-.2,3.25,3.25,0,0,1,1.27.23,1.4,1.4,0,0,1,.72.81c.32.67.7,1.58,1.13,2.71s.91,2.36,1.39,3.68,1,2.66,1.44,4,.91,2.64,1.3,3.82.73,2.19,1,3,.46,1.37.52,1.62a1.31,1.31,0,0,1,.07.44,1.26,1.26,0,0,1-.41,1,1.56,1.56,0,0,1-1.17.39,1.24,1.24,0,0,1-.87-.25,1.66,1.66,0,0,1-.45-.72c-.23-.59-.49-1.34-.8-2.26s-.63-1.92-1-3h-8.45m7.5-2.93c-.48-1.46-.92-2.8-1.35-4S122,31,121.52,29.86c-.11-.25-.23-.53-.35-.87s-.2-.51-.22-.57a2.55,2.55,0,0,0-.22.54c-.13.36-.24.65-.36.9-.45,1.1-.88,2.26-1.3,3.49s-.86,2.56-1.33,4Z" transform="translate(-1.5 -1.16)"/><path class="cls-2" d="M135.65,38.05a2.92,2.92,0,0,1-.32-.38l-.33-.46c-.32-.45-.65-1-1-1.64s-.75-1.32-1.12-2-.73-1.45-1.07-2.18-.68-1.41-.95-2-.53-1.18-.73-1.64a6.56,6.56,0,0,1-.37-1,1.34,1.34,0,0,1-.09-.26s0-.13,0-.25a1.38,1.38,0,0,1,.42-1,1.58,1.58,0,0,1,1.17-.41,1.24,1.24,0,0,1,1,.34,2.2,2.2,0,0,1,.41.67l.33.74c.17.38.38.85.62,1.41s.53,1.18.85,1.86.63,1.33,1,2l.95,1.87a14.31,14.31,0,0,0,.86,1.46,24.85,24.85,0,0,0,1.39-2.47c.49-1,1-1.95,1.41-2.92s.84-1.82,1.18-2.55l.59-1.39a2.23,2.23,0,0,1,.42-.67,1.16,1.16,0,0,1,1-.34,1.56,1.56,0,0,1,1.17.41,1.31,1.31,0,0,1,.42,1,1,1,0,0,1,0,.25l-.08.26-.39,1c-.19.47-.43,1-.72,1.64s-.59,1.31-.93,2-.72,1.45-1.09,2.18-.74,1.4-1.11,2-.72,1.21-1,1.65a5.38,5.38,0,0,1-.65.78v7a1.49,1.49,0,0,1-.42,1.11,1.53,1.53,0,0,1-2.15,0,1.55,1.55,0,0,1-.47-1.15v-7" transform="translate(-1.5 -1.16)"/></svg>
                </button>

                <!-- PayPal -->
                <button type="button" onclick="selectPaymentMethod('paypal')"
                        class="flex-2 payment-method px-3 py-2 border-2 border-gray-300 rounded-xl hover:border-blue-600 hover:bg-blue-50 transition flex items-center justify-center gap-2"
                        data-method="paypal">
                    <svg class="h-6" viewBox="0 0 124 33" fill="none">
                        <path d="M46.211 6.749h-6.839a.95.95 0 0 0-.939.802l-2.766 17.537a.57.57 0 0 0 .564.658h3.265a.95.95 0 0 0 .939-.803l.746-4.73a.95.95 0 0 1 .938-.803h2.165c4.505 0 7.105-2.18 7.784-6.5.306-1.89.013-3.375-.872-4.415-.972-1.142-2.696-1.746-4.985-1.746zM47 13.154c-.374 2.454-2.249 2.454-4.062 2.454h-1.032l.724-4.583a.57.57 0 0 1 .563-.481h.473c1.235 0 2.4 0 3.002.704.359.42.469 1.044.332 1.906zM66.654 13.075h-3.275a.57.57 0 0 0-.563.481l-.145.916-.229-.332c-.709-1.029-2.29-1.373-3.868-1.373-3.619 0-6.71 2.741-7.312 6.586-.313 1.918.132 3.752 1.22 5.031.998 1.176 2.426 1.666 4.125 1.666 2.916 0 4.533-1.875 4.533-1.875l-.146.91a.57.57 0 0 0 .562.66h2.95a.95.95 0 0 0 .939-.803l1.77-11.209a.568.568 0 0 0-.561-.658zm-4.565 6.374c-.316 1.871-1.801 3.127-3.695 3.127-.951 0-1.711-.305-2.199-.883-.484-.574-.668-1.391-.514-2.301.295-1.855 1.805-3.152 3.67-3.152.93 0 1.686.309 2.184.892.499.589.697 1.411.554 2.317zM84.096 13.075h-3.291a.954.954 0 0 0-.787.417l-4.539 6.686-1.924-6.425a.953.953 0 0 0-.912-.678h-3.234a.57.57 0 0 0-.541.754l3.625 10.638-3.408 4.811a.57.57 0 0 0 .465.9h3.287a.949.949 0 0 0 .781-.408l10.946-15.8a.57.57 0 0 0-.468-.895z" fill="#253B80"/>
                        <path d="M94.992 6.749h-6.84a.95.95 0 0 0-.938.802l-2.766 17.537a.569.569 0 0 0 .562.658h3.51a.665.665 0 0 0 .656-.562l.785-4.971a.95.95 0 0 1 .938-.803h2.164c4.506 0 7.105-2.18 7.785-6.5.307-1.89.012-3.375-.873-4.415-.971-1.142-2.694-1.746-4.983-1.746zm.789 6.405c-.373 2.454-2.248 2.454-4.062 2.454h-1.031l.725-4.583a.568.568 0 0 1 .562-.481h.473c1.234 0 2.4 0 3.002.704.359.42.468 1.044.331 1.906zM115.434 13.075h-3.273a.567.567 0 0 0-.562.481l-.145.916-.23-.332c-.709-1.029-2.289-1.373-3.867-1.373-3.619 0-6.709 2.741-7.311 6.586-.312 1.918.131 3.752 1.219 5.031 1 1.176 2.426 1.666 4.125 1.666 2.916 0 4.533-1.875 4.533-1.875l-.146.91a.57.57 0 0 0 .564.66h2.949a.95.95 0 0 0 .938-.803l1.771-11.209a.571.571 0 0 0-.565-.658zm-4.565 6.374c-.314 1.871-1.801 3.127-3.695 3.127-.949 0-1.711-.305-2.199-.883-.484-.574-.666-1.391-.514-2.301.297-1.855 1.805-3.152 3.67-3.152.93 0 1.686.309 2.184.892.501.589.699 1.411.554 2.317zM119.295 7.23l-2.807 17.858a.569.569 0 0 0 .562.658h2.822c.469 0 .867-.34.939-.803l2.768-17.536a.57.57 0 0 0-.562-.659h-3.16a.571.571 0 0 0-.562.482z" fill="#179BD7"/>
                        <path d="M7.266 29.154l.523-3.322-1.165-.027H1.061L4.927 1.292a.316.316 0 0 1 .314-.268h9.38c3.114 0 5.263.648 6.385 1.927.526.6.861 1.227 1.023 1.917.17.724.173 1.589.007 2.644l-.012.077v.676l.526.298a3.69 3.69 0 0 1 1.065.812c.45.513.741 1.165.864 1.938.127.795.085 1.741-.123 2.812-.24 1.232-.628 2.305-1.152 3.183a6.547 6.547 0 0 1-1.825 2c-.696.494-1.523.869-2.458 1.109-.906.236-1.939.355-3.072.355h-.73c-.522 0-1.029.188-1.427.525a2.21 2.21 0 0 0-.744 1.328l-.055.299-.924 5.855-.042.215c-.011.068-.03.102-.058.125a.155.155 0 0 1-.096.035H7.266z" fill="#253B80"/>
                        <path d="M23.048 7.667c-.028.179-.06.362-.096.55-1.237 6.351-5.469 8.545-10.874 8.545H9.326c-.661 0-1.218.48-1.321 1.132L6.596 26.83l-.399 2.533a.704.704 0 0 0 .695.814h4.881c.578 0 1.069-.42 1.16-.99l.048-.248.919-5.832.059-.32c.09-.572.582-.992 1.16-.992h.73c4.729 0 8.431-1.92 9.513-7.476.452-2.321.218-4.259-.978-5.622a4.667 4.667 0 0 0-1.336-1.03z" fill="#179BD7"/>
                        <path d="M21.754 7.151a9.757 9.757 0 0 0-1.203-.267 15.284 15.284 0 0 0-2.426-.177h-7.352a1.172 1.172 0 0 0-1.159.992L8.05 17.605l-.045.289a1.336 1.336 0 0 1 1.321-1.132h2.752c5.405 0 9.637-2.195 10.874-8.545.037-.188.068-.371.096-.55a6.594 6.594 0 0 0-1.017-.429 9.045 9.045 0 0 0-.277-.087z" fill="#222D65"/>
                        <path d="M9.614 7.699a1.169 1.169 0 0 1 1.159-.991h7.352c.871 0 1.684.057 2.426.177a9.757 9.757 0 0 1 1.481.353c.365.121.704.264 1.017.429.368-2.347-.003-3.945-1.272-5.392C20.378.682 17.853 0 14.622 0h-9.38c-.66 0-1.223.48-1.325 1.133L.01 25.898a.806.806 0 0 0 .795.932h5.791l1.454-9.225 1.564-9.906z" fill="#253B80"/>
                    </svg>
                </button>
            </div>

            <!-- Formulário de Cartão -->
            <div id="cardForm" class="hidden space-y-4">
                <div>
                    <label class="block font-medium text-gray-700 mb-2">Card number:</label>
                    <input type="text"
                           id="cardNumber"
                           placeholder="0000 0000 0000 0000"
                           maxlength="19"
                           class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium text-gray-700 mb-2">Expiration Date:</label>
                        <input type="text"
                               id="cardExpiry"
                               placeholder="MM/YY"
                               maxlength="5"
                               class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-2">CVV:</label>
                        <input type="text"
                               id="cardCVV"
                               placeholder="000"
                               maxlength="3"
                               class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Formulário MB Way -->
            <div id="mbwayForm" class="hidden space-y-4">
                <div>
                    <label class="block font-medium text-gray-700 mb-2">Phone number:</label>
                    <div class="flex gap-2">
                        <input type="text"
                               value="+351"
                               readonly
                               class="w-20 px-4 py-2 border border-gray-300 rounded-xl bg-gray-50">
                        <input type="tel"
                               id="mbwayPhone"
                               placeholder="900000000"
                               maxlength="9"
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Formulário PayPal -->
            <div id="paypalForm" class="hidden space-y-4">
                <div>
                    <label class="block font-medium text-gray-700 mb-2">Email / Phone number:</label>
                    <input type="text"
                           id="paypalEmail"
                           placeholder="email@example.com"
                           class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <!-- Total -->
            <div class="mt-6 pt-4 border-t border-gray-200">
                <label class="block font-medium text-gray-700 mb-2">Promo Code</label>
                <div class="flex gap-2">
                    <input type="text"
                           id="promoCodeInput"
                           placeholder="DISCOUNT20"
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent uppercase">
                    <button type="button"
                            onclick="redeemPromoCode()"
                            class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition">
                        Apply
                    </button>
                </div>
                <p id="promoMessage" class="text-sm mt-2 hidden"></p>
            </div>

            <div class="mt-4 p-4 bg-gray-50 rounded-lg text-xl">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 font-medium">Total:</span>
                    <div class="text-right">
                        <span id="originalPriceDisplay" class="block text-sm text-gray-400 line-through hidden">€0.00</span>
                        <span id="paymentAmount" class="font-bold text-gray-900">€0.00</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-gray-200 flex gap-3">
            <button type="button"
                    onclick="closePaymentModal()"
                    class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-semibold transition flex justify-center items-center gap-1">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Cancel
            </button>
            <button type="button"
                    onclick="processPayment()"
                    class="flex-2 px-4 py-2 bg-emerald-800 text-white rounded-xl hover:bg-emerald-200 hover:text-black font-semibold transition">
                Confirm and pay
            </button>
        </div>
    </div>
</div>

<!-- Modal de Reembolso -->
<div id="refundModal" class="hidden fixed inset-0 backdrop-blur-sm z-[9998] backdrop-brightness-50 flex items-center justify-center z-50">
    <div class="text-base bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 z-[9999]">
        <!-- Header -->
        <div class="p-4 border-b border-gray-200 bg-gray-600 text-white text-xl font-bold  rounded-t-2xl">
            <h2>Booking Update - Refund</h2>
        </div>

        <!-- Body -->
        <div class="p-6">
            <p class="text-gray-600 mb-4">
                The new booking price is lower than the original. You will receive a refund for the difference.
            </p>

            <!-- Price Breakdown -->
            <div class="space-y-3 mb-6">
                <div class="flex justify-between items-center px-4 py-2 bg-gray-50 rounded-2xl">
                    <span class="text-gray-600 font-medium">Original price:</span>
                    <span class="font-semibold text-gray-900">€<span id="refundOldPrice">0.00</span></span>
                </div>

                <div class="flex justify-between items-center px-4 py-2 bg-gray-50 rounded-2xl">
                    <span class="text-gray-600 font-medium">New price:</span>
                    <span class="font-semibold text-gray-900">€<span id="refundNewPrice">0.00</span></span>
                </div>

                <div class="flex justify-between items-center px-4 py-2 bg-green-50 border border-green-200 rounded-2xl">
                    <span class="text-green-700 font-semibold">Refund amount:</span>
                    <span class="font-bold text-green-700 text-lg">€<span id="refundAmount">0.00</span></span>
                </div>
            </div>

            <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                <p class="text-sm text-blue-800">
                    <svg class="w-5 h-5 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    The refund will be processed to your original payment method within 5-7 business days.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-gray-200 flex gap-3">
            <button type="button"
                    onclick="closeRefundModal()"
                    class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-semibold transition">
                Cancel
            </button>
            <button type="button"
                    onclick="confirmRefund()"
                    class="flex-2 px-4 py-2 bg-green-800 text-white rounded-xl hover:bg-green-200 hover:text-black font-semibold transition">
                Confirm update
            </button>
        </div>
    </div>
</div>