/**
 * Alize Travel - SEO ve Analytics Tracking
 * Google Analytics, Google Tag Manager ve diğer SEO araçları
 */

// Google Analytics 4 (GA4) - G-RP8G9FGLL4
function initGoogleAnalytics() {
    // Google Analytics 4
    window.dataLayer = window.dataLayer || [];
    window.gtag = window.gtag || function(){dataLayer.push(arguments);};
    gtag('js', new Date());
    gtag('config', 'G-RP8G9FGLL4', {
        page_title: document.title,
        page_location: window.location.href,
        custom_map: {
            'custom_parameter_1': 'tour_category',
            'custom_parameter_2': 'guide_name'
        }
    });
}

// Google Tag Manager
function initGoogleTagManager() {
    (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-XXXXXXX');
}

// Facebook Pixel
function initFacebookPixel() {
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', 'YOUR_PIXEL_ID');
    fbq('track', 'PageView');
}

// Yandex Metrica
function initYandexMetrica() {
    (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
    m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
    ym(YOUR_COUNTER_ID, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
    });
}

// SEO Event Tracking
function trackSEOEevents() {
    // Tur detay sayfası ziyareti
    if (window.location.pathname.includes('/tur/')) {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'view_tour_detail', {
                'tour_id': getTourIdFromUrl(),
                'tour_title': document.title
            });
        }
    }
    
    // İletişim formu gönderimi
    const contactForms = document.querySelectorAll('form[action*="contact"]');
    contactForms.forEach(form => {
        form.addEventListener('submit', function() {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'contact_form_submit', {
                    'form_name': 'contact_form',
                    'page_location': window.location.href
                });
            }
        });
    });
    
    // WhatsApp buton tıklaması
    const whatsappButtons = document.querySelectorAll('a[href*="wa.me"]');
    whatsappButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'whatsapp_click', {
                    'button_location': this.closest('section')?.id || 'unknown',
                    'page_location': window.location.href
                });
            }
        });
    });
    
    // Telefon numarası tıklaması
    const phoneLinks = document.querySelectorAll('a[href^="tel:"]');
    phoneLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'phone_click', {
                    'phone_number': this.href.replace('tel:', ''),
                    'page_location': window.location.href
                });
            }
        });
    });
}

// URL'den tur ID'sini çıkarma
function getTourIdFromUrl() {
    const pathParts = window.location.pathname.split('/');
    const tourIndex = pathParts.indexOf('tur');
    return tourIndex !== -1 && pathParts[tourIndex + 1] ? pathParts[tourIndex + 1] : null;
}

// Sayfa yüklendiğinde çalıştır
document.addEventListener('DOMContentLoaded', function() {
    // Analytics araçlarını başlat
    initGoogleAnalytics();
    initGoogleTagManager();
    initFacebookPixel();
    initYandexMetrica();
    
    // SEO event tracking'i başlat
    trackSEOEevents();
    
    // Sayfa görünümü eventi - gtag yüklendikten sonra
    setTimeout(function() {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'page_view', {
                'page_title': document.title,
                'page_location': window.location.href,
                'page_path': window.location.pathname
            });
        }
    }, 1000);
});

// Sayfa değişimlerini takip et (SPA için)
window.addEventListener('popstate', function() {
    if (typeof gtag !== 'undefined') {
        gtag('event', 'page_view', {
            'page_title': document.title,
            'page_location': window.location.href,
            'page_path': window.location.pathname
        });
    }
});

// Core Web Vitals tracking
function trackCoreWebVitals() {
    // Largest Contentful Paint (LCP)
    new PerformanceObserver((entryList) => {
        for (const entry of entryList.getEntries()) {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'web_vitals', {
                    'name': 'LCP',
                    'value': Math.round(entry.startTime),
                    'event_category': 'Web Vitals'
                });
            }
        }
    }).observe({entryTypes: ['largest-contentful-paint']});
    
    // First Input Delay (FID)
    new PerformanceObserver((entryList) => {
        for (const entry of entryList.getEntries()) {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'web_vitals', {
                    'name': 'FID',
                    'value': Math.round(entry.processingStart - entry.startTime),
                    'event_category': 'Web Vitals'
                });
            }
        }
    }).observe({entryTypes: ['first-input']});
    
    // Cumulative Layout Shift (CLS)
    let clsValue = 0;
    new PerformanceObserver((entryList) => {
        for (const entry of entryList.getEntries()) {
            if (!entry.hadRecentInput) {
                clsValue += entry.value;
            }
        }
        if (typeof gtag !== 'undefined') {
            gtag('event', 'web_vitals', {
                'name': 'CLS',
                'value': Math.round(clsValue * 1000),
                'event_category': 'Web Vitals'
            });
        }
    }).observe({entryTypes: ['layout-shift']});
}

// Core Web Vitals tracking'i başlat
if ('PerformanceObserver' in window) {
    trackCoreWebVitals();
}
