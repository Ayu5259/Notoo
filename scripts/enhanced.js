// Enhanced JavaScript for Better User Experience

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add loading animation to buttons
    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('click', function() {
            if (!this.classList.contains('btn-loading')) {
                this.classList.add('btn-loading');
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> در حال پردازش...';
                
                // Reset button after 2 seconds (for demo purposes)
                setTimeout(() => {
                    this.classList.remove('btn-loading');
                    this.innerHTML = originalText;
                }, 2000);
            }
        });
    });

    // Add hover effects to cards
    document.querySelectorAll('.card, .content .box').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Form validation enhancement
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    
                    // Add error message
                    if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('invalid-feedback')) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = 'این فیلد الزامی است';
                        field.parentNode.appendChild(errorDiv);
                    }
                } else {
                    field.classList.remove('is-invalid');
                    const errorDiv = field.parentNode.querySelector('.invalid-feedback');
                    if (errorDiv) {
                        errorDiv.remove();
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showNotification('لطفاً تمام فیلدهای الزامی را پر کنید', 'error');
            }
        });
    });

    // Enhanced table interactions
    document.querySelectorAll('.table tbody tr').forEach(row => {
        row.addEventListener('click', function() {
            // Remove active class from all rows
            document.querySelectorAll('.table tbody tr').forEach(r => {
                r.classList.remove('table-active');
            });
            // Add active class to clicked row
            this.classList.add('table-active');
        });
    });

    // Auto-hide alerts after 5 seconds
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            if (alert.parentNode) {
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }
        }, 5000);
    });

    // Enhanced search functionality
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Add loading state
                this.classList.add('loading');
                
                // Simulate search delay
                setTimeout(() => {
                    this.classList.remove('loading');
                }, 500);
            }, 300);
        });
    }

    // Priority color coding
    document.querySelectorAll('[data-priority]').forEach(element => {
        const priority = element.getAttribute('data-priority');
        switch(priority) {
            case 'high':
                element.classList.add('priority-high');
                break;
            case 'medium':
                element.classList.add('priority-medium');
                break;
            case 'low':
                element.classList.add('priority-low');
                break;
        }
    });

    // Task completion animation
    document.querySelectorAll('a[href*="done"]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const taskCard = this.closest('.card, .list-group-item');
            if (taskCard) {
                taskCard.style.transition = 'all 0.5s ease';
                taskCard.style.transform = 'scale(0.95)';
                taskCard.style.opacity = '0.5';
                
                setTimeout(() => {
                    window.location.href = this.href;
                }, 500);
            } else {
                window.location.href = this.href;
            }
        });
    });

    // Enhanced modal interactions
    document.querySelectorAll('[data-bs-toggle="modal"]').forEach(trigger => {
        trigger.addEventListener('click', function() {
            const targetModal = document.querySelector(this.getAttribute('data-bs-target'));
            if (targetModal) {
                targetModal.addEventListener('shown.bs.modal', function() {
                    // Focus on first input in modal
                    const firstInput = this.querySelector('input, textarea, select');
                    if (firstInput) {
                        firstInput.focus();
                    }
                });
            }
        });
    });

    // Date picker enhancement
    document.querySelectorAll('input[type="date"]').forEach(dateInput => {
        dateInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            
            if (selectedDate < today) {
                this.classList.add('is-invalid');
                showNotification('تاریخ انتخاب شده در گذشته است', 'warning');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });

    // Category color preview
    document.querySelectorAll('input[type="color"]').forEach(colorInput => {
        colorInput.addEventListener('change', function() {
            const preview = this.parentNode.querySelector('.color-preview');
            if (preview) {
                preview.style.backgroundColor = this.value;
            }
        });
    });

    // Responsive sidebar toggle for mobile
    const sidebarToggle = document.querySelector('#sidebarToggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            // Change icon
            const icon = this.querySelector('i');
            if (sidebar.classList.contains('show')) {
                icon.className = 'fas fa-times';
            } else {
                icon.className = 'fas fa-bars';
            }
        });
        
        // Close sidebar when clicking outside
        document.addEventListener('click', function(e) {
            if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                sidebar.classList.remove('show');
                const icon = sidebarToggle.querySelector('i');
                icon.className = 'fas fa-bars';
            }
        });
    }

    // Infinite scroll for long lists
    let isLoading = false;
    window.addEventListener('scroll', function() {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 1000) {
            if (!isLoading) {
                isLoading = true;
                // Load more content here
                setTimeout(() => {
                    isLoading = false;
                }, 1000);
            }
        }
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + N for new task
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            const newTaskModal = document.querySelector('#addTaskModal');
            if (newTaskModal) {
                const modal = new bootstrap.Modal(newTaskModal);
                modal.show();
            }
        }
        
        // Ctrl/Cmd + S for search
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.focus();
            }
        }
        
        // Escape to close modals
        if (e.key === 'Escape') {
            const openModals = document.querySelectorAll('.modal.show');
            openModals.forEach(modal => {
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) {
                    modalInstance.hide();
                }
            });
        }
    });

    // Notification system
    window.showNotification = function(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    };

    // Progress bar for task completion
    function updateProgressBar() {
        const totalTasks = document.querySelectorAll('.task-item').length;
        const completedTasks = document.querySelectorAll('.task-item.completed').length;
        const progressBar = document.querySelector('.progress-bar');
        
        if (progressBar && totalTasks > 0) {
            const percentage = (completedTasks / totalTasks) * 100;
            progressBar.style.width = percentage + '%';
            progressBar.textContent = Math.round(percentage) + '%';
        }
    }

    // Initialize progress bar
    updateProgressBar();

    // Add animation classes to elements when they come into view
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);

    // Observe all cards and boxes
    document.querySelectorAll('.card, .content .box, .feature-card').forEach(el => {
        observer.observe(el);
    });

    // Enhanced form submission feedback
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> در حال ارسال...';
            }
        });
    });

    // Edit user function
    window.editUser = function(userId) {
        // Get user data via AJAX
        fetch('inc/functions.php?get-user=' + userId)
            .then(response => response.json())
            .then(user => {
                // Fill the modal with user data
                document.getElementById('editUserId').value = user.id;
                document.getElementById('editDisplayName').value = user.display_name;
                document.getElementById('editUsername').value = user.username;
                document.getElementById('editUserType').value = user.is_admin;
                
                // Show the modal
                const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
                modal.show();
            })
            .catch(error => {
                showNotification('خطا در دریافت اطلاعات کاربر', 'error');
                console.error('Error:', error);
            });
    }

    // Confirm delete user modal
    window.confirmDeleteUser = function(userId, displayName) {
        const modalHtml = `
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">تایید حذف کاربر</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>آیا مطمئن هستید که می‌خواهید کاربر <strong>${displayName}</strong> را حذف کنید؟ این عملیات قابل بازگشت نیست.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                        <a href="inc/functions.php?delete-user=${userId}" class="btn btn-danger">حذف</a>
                    </div>
                </div>
            </div>
        </div>`;
        // Remove any existing modal
        const oldModal = document.getElementById('confirmDeleteModal');
        if (oldModal) oldModal.remove();
        // Append new modal
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        modal.show();
    }

    // Open change password modal for admin
    window.openChangePasswordModal = function(userId) {
        const modal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
        document.getElementById('changePasswordUserId').value = userId;
        modal.show();
    }

    // Password change form validation
    const passwordChangeForm = document.querySelector('form[name="change-password"]');
    if (passwordChangeForm) {
        const newPasswordInput = passwordChangeForm.querySelector('input[name="new-password"]');
        const confirmPasswordInput = passwordChangeForm.querySelector('input[name="confirm-password"]');
        
        function validatePasswordChange() {
            const newPassword = newPasswordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            
            // Remove existing error messages
            newPasswordInput.classList.remove('is-invalid');
            confirmPasswordInput.classList.remove('is-invalid');
            
            const newPasswordError = newPasswordInput.parentNode.querySelector('.invalid-feedback');
            const confirmPasswordError = confirmPasswordInput.parentNode.querySelector('.invalid-feedback');
            
            if (newPasswordError) newPasswordError.remove();
            if (confirmPasswordError) confirmPasswordError.remove();
            
            let isValid = true;
            
            // Check password length
            if (newPassword.length < 6) {
                isValid = false;
                newPasswordInput.classList.add('is-invalid');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = 'رمز عبور باید حداقل 6 کاراکتر باشد';
                newPasswordInput.parentNode.appendChild(errorDiv);
            }
            
            // Check if passwords match
            if (newPassword !== confirmPassword) {
                isValid = false;
                confirmPasswordInput.classList.add('is-invalid');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = 'رمز عبور و تکرار آن باهم برابر نیستند';
                confirmPasswordInput.parentNode.appendChild(errorDiv);
            }
            
            return isValid;
        }
        
        // Add event listeners for real-time validation
        newPasswordInput.addEventListener('input', validatePasswordChange);
        confirmPasswordInput.addEventListener('input', validatePasswordChange);
        
        // Add form submission validation
        passwordChangeForm.addEventListener('submit', function(e) {
            if (!validatePasswordChange()) {
                e.preventDefault();
                showNotification('لطفاً خطاهای فرم را برطرف کنید', 'error');
            }
        });
    }

    console.log('Enhanced JavaScript loaded successfully!');
});

// CSS for animations
const style = document.createElement('style');
style.textContent = `
    .animate-in {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    
    .btn-loading {
        pointer-events: none;
    }
    
    .table-active {
        background-color: rgba(102, 126, 234, 0.1) !important;
    }
    
    .loading {
        animation: pulse 1.5s infinite;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    
    .color-preview {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 2px solid #ddd;
        display: inline-block;
        margin-right: 10px;
    }
`;
document.head.appendChild(style);
 