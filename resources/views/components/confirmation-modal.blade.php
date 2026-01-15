<!-- Modern Confirmation Modal Component -->
<div id="confirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-center w-12 h-12 mx-auto rounded-full mb-4" id="modalIcon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="modalIconSvg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="text-center">
                <h3 class="text-lg font-medium text-gray-900 mb-2" id="modalTitle">Confirm Action</h3>
                <p class="text-sm text-gray-500 mb-4" id="modalMessage">Are you sure you want to perform this action?</p>

                <!-- Password input for sensitive actions -->
                <div id="passwordSection" class="mb-6 hidden">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <span class="text-sm font-medium text-red-800">Security Verification Required</span>
                        </div>
                        <p class="text-xs text-red-700">This is a sensitive action. Please enter your password to confirm.</p>
                        <div class="mt-2 flex items-center text-xs text-red-600">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span id="currentAdminInfo">Verifying as current admin</span>
                        </div>
                    </div>
                    <div class="text-left">
                        <label for="adminPassword" class="block text-sm font-medium text-gray-700 mb-2">Admin Password</label>
                        <input type="password"
                               id="adminPassword"
                               name="admin_password"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                               placeholder="Enter your password"
                               autocomplete="current-password">
                        <div id="passwordError" class="text-red-600 text-xs mt-1 hidden"></div>
                    </div>
                </div>

                <div class="flex justify-center space-x-3">
                    <button type="button"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors"
                            onclick="closeConfirmModal()">
                        Cancel
                    </button>
                    <button type="button"
                            class="px-4 py-2 rounded-lg text-white transition-colors"
                            id="confirmButton"
                            onclick="executeAction()">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for submissions -->
<form id="actionForm" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

<script>
    let currentAction = '';
    let currentTarget = '';
    let currentActionUrl = '';
    let currentMethod = 'PATCH';

    function openConfirmModal(action, target, actionUrl, method = 'PATCH') {
        currentAction = action;
        currentTarget = target;
        currentActionUrl = actionUrl;
        currentMethod = method;
        
        const modal = document.getElementById('confirmModal');
        const modalIcon = document.getElementById('modalIcon');
        const modalIconSvg = document.getElementById('modalIconSvg');
        const modalTitle = document.getElementById('modalTitle');
        const modalMessage = document.getElementById('modalMessage');
        const confirmButton = document.getElementById('confirmButton');
        
        // Configure modal based on action type
        const passwordSection = document.getElementById('passwordSection');
        const adminPasswordInput = document.getElementById('adminPassword');
        const passwordError = document.getElementById('passwordError');

        // Reset password section
        passwordSection.classList.add('hidden');
        adminPasswordInput.value = '';
        passwordError.classList.add('hidden');
        passwordError.textContent = '';

        switch(action) {
            case 'suspend':
                modalIcon.className = 'flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4';
                modalIconSvg.className = 'w-6 h-6 text-red-600';
                modalIconSvg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>';
                modalTitle.textContent = 'Suspend Club';
                modalMessage.innerHTML = `Are you sure you want to suspend <strong>${target}</strong>?<br><span class="text-xs text-gray-400 mt-1 block">This action will deactivate the club and restrict their activities.</span>`;
                confirmButton.className = 'px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors';
                confirmButton.textContent = 'Suspend Club';
                // Show password section for suspend action
                passwordSection.classList.remove('hidden');
                // Load current admin info
                loadCurrentAdminInfo();
                setTimeout(() => adminPasswordInput.focus(), 100);
                break;
                
            case 'resume':
                modalIcon.className = 'flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full mb-4';
                modalIconSvg.className = 'w-6 h-6 text-green-600';
                modalIconSvg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m2-10v18a2 2 0 01-2 2H6a2 2 0 01-2-2V4a2 2 0 012-2h8a2 2 0 012 2z"></path>';
                modalTitle.textContent = 'Resume Club';
                modalMessage.innerHTML = `Are you sure you want to resume <strong>${target}</strong>?<br><span class="text-xs text-gray-400 mt-1 block">This action will restore the club's active status and privileges.</span>`;
                confirmButton.className = 'px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors';
                confirmButton.textContent = 'Resume Club';
                // Show password section for resume action (same as suspend)
                passwordSection.classList.remove('hidden');
                // Load current admin info
                loadCurrentAdminInfo();
                setTimeout(() => adminPasswordInput.focus(), 100);
                break;
                
            case 'verify':
                modalIcon.className = 'flex items-center justify-center w-12 h-12 mx-auto bg-blue-100 rounded-full mb-4';
                modalIconSvg.className = 'w-6 h-6 text-blue-600';
                modalIconSvg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                modalTitle.textContent = 'Verify Registration';
                modalMessage.innerHTML = `Are you sure you want to verify the registration for <strong>${target}</strong>?<br><span class="text-xs text-gray-400 mt-1 block">This action will mark the registration as verified by your office.</span>`;
                confirmButton.className = 'px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors';
                confirmButton.textContent = 'Verify Registration';
                // Show password section for verify action
                passwordSection.classList.remove('hidden');
                loadCurrentAdminInfo();
                setTimeout(() => adminPasswordInput.focus(), 100);
                break;
                
            case 'approve':
                modalIcon.className = 'flex items-center justify-center w-12 h-12 mx-auto bg-green-100 rounded-full mb-4';
                modalIconSvg.className = 'w-6 h-6 text-green-600';
                modalIconSvg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                modalTitle.textContent = 'Approve Registration';
                modalMessage.innerHTML = `Are you sure you want to approve the registration for <strong>${target}</strong>?<br><span class="text-xs text-gray-400 mt-1 block">This action will approve the registration application.</span>`;
                confirmButton.className = 'px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors';
                confirmButton.textContent = 'Approve Registration';
                // Show password section for approve action
                passwordSection.classList.remove('hidden');
                loadCurrentAdminInfo();
                setTimeout(() => adminPasswordInput.focus(), 100);
                break;
                
            case 'note':
                modalIcon.className = 'flex items-center justify-center w-12 h-12 mx-auto bg-yellow-100 rounded-full mb-4';
                modalIconSvg.className = 'w-6 h-6 text-yellow-600';
                modalIconSvg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>';
                modalTitle.textContent = 'Note Registration';
                modalMessage.innerHTML = `Are you sure you want to note the registration for <strong>${target}</strong>?<br><span class="text-xs text-gray-400 mt-1 block">This action will mark the registration as noted by your office.</span>`;
                confirmButton.className = 'px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors';
                confirmButton.textContent = 'Note Registration';
                // Show password section for note action
                passwordSection.classList.remove('hidden');
                loadCurrentAdminInfo();
                setTimeout(() => adminPasswordInput.focus(), 100);
                break;
                
            case 'endorse':
                modalIcon.className = 'flex items-center justify-center w-12 h-12 mx-auto bg-purple-100 rounded-full mb-4';
                modalIconSvg.className = 'w-6 h-6 text-purple-600';
                modalIconSvg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                modalTitle.textContent = 'Endorse Registration';
                modalMessage.innerHTML = `Are you sure you want to endorse the registration for <strong>${target}</strong>?<br><span class="text-xs text-gray-400 mt-1 block">This action will endorse the registration application.</span>`;
                confirmButton.className = 'px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors';
                confirmButton.textContent = 'Endorse Registration';
                // Show password section for endorse action
                passwordSection.classList.remove('hidden');
                loadCurrentAdminInfo();
                setTimeout(() => adminPasswordInput.focus(), 100);
                break;
                
            default:
                modalIcon.className = 'flex items-center justify-center w-12 h-12 mx-auto bg-gray-100 rounded-full mb-4';
                modalIconSvg.className = 'w-6 h-6 text-gray-600';
                modalIconSvg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>';
                modalTitle.textContent = 'Confirm Action';
                modalMessage.innerHTML = `Are you sure you want to perform this action?`;
                confirmButton.className = 'px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors';
                confirmButton.textContent = 'Confirm';
        }
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeConfirmModal() {
        const modal = document.getElementById('confirmModal');
        const adminPasswordInput = document.getElementById('adminPassword');
        const passwordError = document.getElementById('passwordError');
        const confirmButton = document.getElementById('confirmButton');

        // Reset modal state
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';

        // Reset password field and errors
        adminPasswordInput.value = '';
        passwordError.classList.add('hidden');
        passwordError.textContent = '';

        // Reset button state
        confirmButton.disabled = false;
    }

    function executeAction() {
        const form = document.getElementById('actionForm');
        const passwordSection = document.getElementById('passwordSection');
        const adminPasswordInput = document.getElementById('adminPassword');
        const passwordError = document.getElementById('passwordError');
        const confirmButton = document.getElementById('confirmButton');

        // Check if password is required (for sensitive actions)
        if (currentAction === 'suspend' || currentAction === 'resume' || currentAction === 'verify' ||
            currentAction === 'approve' || currentAction === 'note' || currentAction === 'endorse') {
            const password = adminPasswordInput.value.trim();

            if (!password) {
                passwordError.textContent = 'Password is required for this action.';
                passwordError.classList.remove('hidden');
                adminPasswordInput.focus();
                return;
            }

            // Disable button and show loading state
            confirmButton.disabled = true;
            confirmButton.textContent = 'Verifying...';
            confirmButton.className = 'px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed';

            // Verify password via AJAX
            fetch('/admin/verify-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    password: password
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Password verified, proceed with action
                    submitForm();
                } else {
                    // Password incorrect
                    passwordError.textContent = data.message || 'Incorrect password. Please try again.';
                    passwordError.classList.remove('hidden');
                    adminPasswordInput.focus();
                    adminPasswordInput.select();

                    // Reset button
                    confirmButton.disabled = false;
                    if (currentAction === 'suspend') {
                        confirmButton.textContent = 'Suspend Club';
                        confirmButton.className = 'px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors';
                    } else if (currentAction === 'resume') {
                        confirmButton.textContent = 'Resume Club';
                        confirmButton.className = 'px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors';
                    } else if (currentAction === 'verify') {
                        confirmButton.textContent = 'Verify Registration';
                        confirmButton.className = 'px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors';
                    } else if (currentAction === 'approve') {
                        confirmButton.textContent = 'Approve Registration';
                        confirmButton.className = 'px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors';
                    } else if (currentAction === 'note') {
                        confirmButton.textContent = 'Note Registration';
                        confirmButton.className = 'px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors';
                    } else if (currentAction === 'endorse') {
                        confirmButton.textContent = 'Endorse Registration';
                        confirmButton.className = 'px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors';
                    }
                }
            })
            .catch(error => {
                console.error('Password verification error:', error);
                passwordError.textContent = 'Verification failed. Please try again.';
                passwordError.classList.remove('hidden');

                // Reset button
                confirmButton.disabled = false;
                if (currentAction === 'suspend') {
                    confirmButton.textContent = 'Suspend Club';
                    confirmButton.className = 'px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors';
                } else if (currentAction === 'resume') {
                    confirmButton.textContent = 'Resume Club';
                    confirmButton.className = 'px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors';
                } else if (currentAction === 'verify') {
                    confirmButton.textContent = 'Verify Registration';
                    confirmButton.className = 'px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors';
                } else if (currentAction === 'approve') {
                    confirmButton.textContent = 'Approve Registration';
                    confirmButton.className = 'px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors';
                } else if (currentAction === 'note') {
                    confirmButton.textContent = 'Note Registration';
                    confirmButton.className = 'px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors';
                } else if (currentAction === 'endorse') {
                    confirmButton.textContent = 'Endorse Registration';
                    confirmButton.className = 'px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors';
                }
            });
        } else {
            // No password required, proceed directly
            submitForm();
        }
    }

    function submitForm() {
        const form = document.getElementById('actionForm');
        form.action = currentActionUrl;

        // Update method field
        const methodField = form.querySelector('input[name="_method"]');
        if (methodField) {
            methodField.value = currentMethod;
        } else if (currentMethod !== 'POST') {
            const newMethodField = document.createElement('input');
            newMethodField.type = 'hidden';
            newMethodField.name = '_method';
            newMethodField.value = currentMethod;
            form.appendChild(newMethodField);
        }

        // Set navigation flag before form submission to prevent session logout
        if (typeof setNavigationFlag === 'function') {
            setNavigationFlag();
        }

        // Small delay to ensure navigation flag is set
        setTimeout(() => {
            form.submit();
        }, 100);
    }

    function loadCurrentAdminInfo() {
        const currentAdminInfo = document.getElementById('currentAdminInfo');

        // Fetch current admin info
        fetch('/admin/current-info', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentAdminInfo.innerHTML = `Verifying as: <strong>${data.name}</strong> (${data.role})`;
            } else {
                currentAdminInfo.textContent = 'Verifying as current admin';
            }
        })
        .catch(error => {
            console.error('Error loading admin info:', error);
            currentAdminInfo.textContent = 'Verifying as current admin';
        });
    }

    // Close modal when clicking outside
    document.getElementById('confirmModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeConfirmModal();
        }
    });

    // Close modal with Escape key and handle Enter key for password
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeConfirmModal();
        } else if (e.key === 'Enter' && e.target.id === 'adminPassword') {
            executeAction();
        }
    });
</script>
