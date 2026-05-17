import './bootstrap';
import './bootstrap';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

// গ্লোবাল উইন্ডোতে ভাইট সাকসেস চেক করার জন্য একটি টেস্ট ভেরিয়েবল
window.ViteLoaded = true;
console.log('🚀 Borobhai App: Vite has been successfully compiled and loaded!');

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
