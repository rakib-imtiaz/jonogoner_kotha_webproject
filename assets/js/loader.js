// LoaderAnimation.js
function showLoader() {
    // Create a loader container
    const loaderContainer = document.createElement('div');
    loaderContainer.id = 'loader-container';
    loaderContainer.style.position = 'fixed';
    loaderContainer.style.top = '0';
    loaderContainer.style.left = '0';
    loaderContainer.style.width = '100%';
    loaderContainer.style.height = '100%';
    loaderContainer.style.backgroundColor = 'rgba(0,0,0,0.5)';
    loaderContainer.style.display = 'flex';
    loaderContainer.style.justifyContent = 'center';
    loaderContainer.style.alignItems = 'center';
    loaderContainer.style.zIndex = '9999';

    // Create loader
    const loader = document.createElement('div');
    loader.className = 'loader';
    loader.style.border = '16px solid #f3f3f3';
    loader.style.borderTop = '16px solid #3498db';
    loader.style.borderRadius = '50%';
    loader.style.width = '120px';
    loader.style.height = '120px';
    loader.style.animation = 'spin 2s linear infinite';

    // Append loader to the container
    loaderContainer.appendChild(loader);

    // Append container to the body
    document.body.appendChild(loaderContainer);

    // Optional: Remove loader after 3 seconds
    setTimeout(() => {
        document.body.removeChild(loaderContainer);
    }, 5000); // Adjust delay as needed
}

// CSS for loader animation
const style = document.createElement('style');
style.innerHTML = `
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loader {
    border: 16px solid #f3f3f3;
    border-top: 16px solid #3498db;
    border-radius: 50%;
    width: 120px;
    height: 120px;
    animation: spin 2s linear infinite;
}
`;
document.head.appendChild(style);

