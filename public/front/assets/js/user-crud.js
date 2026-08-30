/* JustGoom User Panel — CRUD form helpers (static demo) */
(function() {
  var CRUD = {
    banner: {
      list: 'banners.html',
      label: 'Banner',
      items: {
        '1': { title: 'Flat 20% Off — Wedding Collection', description: 'Exclusive wedding season offer on our premium 22K collection.', cta: 'View Business', url: 'https://justgoom.com/profile/shree-gold', slot: '1', status: 'Live', image: '../assets/images/cat-business.jpg' },
        '2': { title: 'Custom Gold Design Services', description: 'Bespoke necklace and ring design with certified gold.', cta: 'Learn More', url: 'https://justgoom.com/profile/shree-gold', slot: '2', status: 'Live', image: '../assets/images/cat-real-estate.jpg' },
        '3': { title: 'B2B Bulk Gold Supply Offer', description: 'Wholesale rates for verified B2B buyers.', cta: 'Contact Us', url: 'https://justgoom.com/profile/shree-gold', slot: '', status: 'Pending', image: '../assets/images/blog-1.jpg' }
      }
    },
    video: {
      list: 'videos.html',
      label: 'Video',
      items: {
        '1': { title: 'Wedding Collection 2026 Showcase', description: 'A walkthrough of our latest wedding jewellery line.', duration: '1:24', status: 'Live' },
        '2': { title: 'Custom Design Process Tour', description: 'See how we craft custom pieces from sketch to finish.', duration: '0:58', status: 'Live' },
        '3': { title: 'B2B Bulk Order Walkthrough', description: 'How our wholesale ordering process works.', duration: '2:05', status: 'Pending' },
        '4': { title: '22K Gold Purity Explained', description: 'Education video on gold purity and hallmarking.', duration: '1:10', status: 'Live' }
      }
    },
    article: {
      list: 'articles.html',
      label: 'Article',
      items: {
        '1': { title: 'Why 22K Gold is the Smart Choice', category: 'Jewellery', visibility: 'Global', status: 'Published', content: 'When investing in gold jewellery for weddings and festivals, 22K gold offers the ideal balance of purity and durability...' },
        '2': { title: 'Understanding Gold Making Charges', category: 'Business', visibility: 'Global', status: 'Published', content: 'Making charges are a key component of your final jewellery bill. Here is how to evaluate them transparently...' },
        '3': { title: 'B2B Bulk Gold Supply Guide', category: 'B2B Trade', visibility: 'Global', status: 'Published', content: 'For retailers and wholesalers looking to partner with certified suppliers, this guide covers due diligence steps...' },
        '4': { title: 'Wedding Season Trends 2026', category: 'Jewellery', visibility: 'Global', status: 'Draft', content: 'This season brings minimalist bridal sets, layered necklaces, and heritage-inspired motifs...' }
      }
    },
    service: {
      list: 'services.html',
      label: 'Service',
      items: {
        '1': { name: '22K Gold Jewellery', category: 'Retail', description: 'Premium 22K gold ornaments for retail customers.', status: 'Active', price: '₹5,000 – ₹5,00,000', order: '1', featured: true },
        '2': { name: 'Wedding Collection', category: 'Retail', description: 'Curated bridal sets and wedding jewellery packages.', status: 'Active', price: '₹25,000 – ₹10,00,000', order: '2', featured: true },
        '3': { name: 'Gold Bullion', category: 'Wholesale', description: 'Certified gold bullion for wholesale buyers.', status: 'Active', price: 'Market rate', order: '3', featured: false },
        '4': { name: 'Custom Design', category: 'Service', description: 'Custom necklace, ring, and bangle design services.', status: 'Active', price: '₹2,000+ making charges', order: '4', featured: false },
        '5': { name: 'B2B Wholesale Supply', category: 'Wholesale', description: 'Long-term B2B supply partnerships.', status: 'Active', price: 'Bulk pricing', order: '5', featured: true },
        '6': { name: 'Gold Loan Assistance', category: 'Finance', description: 'Assistance with gold loan applications.', status: 'Active', price: 'Free consultation', order: '6', featured: false },
        '7': { name: 'Making Charges Calculator', category: 'Tool', description: 'Online tool to estimate making charges.', status: 'Active', price: 'Free', order: '7', featured: false }
      }
    },
    team: {
      list: 'team.html',
      label: 'Team Member',
      items: {
        '1': { name: 'Patel Ramesh', role: 'Owner', email: 'ramesh@shreegold.com', phone: '+91 98765 43210', department: 'Management', status: 'Active', bio: 'Founder and owner overseeing retail and wholesale operations.', primary: true },
        '2': { name: 'Shah Priya', role: 'Sales Manager', email: 'priya@shreegold.com', phone: '+91 98765 43211', department: 'Sales', status: 'Active', bio: 'Handles retail sales and customer relationships.', primary: false },
        '3': { name: 'Mehta Vikram', role: 'Wholesale Lead', email: 'vikram@shreegold.com', phone: '+91 98765 43212', department: 'Wholesale', status: 'Active', bio: 'Manages B2B partnerships and bulk orders.', primary: false }
      }
    },
    document: {
      list: 'documents.html',
      label: 'Document',
      items: {
        '1': { name: 'GST Registration Certificate', type: 'PDF', uploaded: 'Jan 15, 2026' },
        '2': { name: 'BIS Hallmark License', type: 'PDF', uploaded: 'Jan 15, 2026' },
        '3': { name: 'Business Registration', type: 'PDF', uploaded: 'Dec 10, 2025' },
        '4': { name: 'Product Catalogue 2026', type: 'PDF', uploaded: 'May 1, 2026' }
      }
    },
    inquiry: {
      list: 'inquiries.html',
      label: 'Inquiry',
      items: {
        '1': { from: 'Raj Kumar', email: 'raj@example.com', phone: '+91 99887 76655', subject: 'Gold Bulk Order — 22K wedding collection', date: 'May 31, 2026', status: 'New', message: 'Hello, we are interested in a bulk order of 22K wedding collection pieces for our retail chain. Please share catalogue and wholesale pricing.' },
        '2': { from: 'Priya Sharma', email: 'priya.s@example.com', phone: '+91 98765 11122', subject: 'Custom necklace design request', date: 'May 30, 2026', status: 'Replied', message: 'I would like a custom necklace designed for my wedding. Can you share the design process and timeline?', reply: 'Thank you for reaching out! Our design team will contact you within 24 hours.' },
        '3': { from: 'Amit Mehta', email: 'amit@example.com', phone: '+91 91234 56789', subject: 'B2B long-term supply partnership', date: 'May 28, 2026', status: 'New', message: 'We operate 12 retail outlets across Gujarat and are looking for a long-term gold supply partner.' }
      }
    },
    review: {
      list: 'reviews.html',
      label: 'Review',
      items: {
        '1': { author: 'Raj Kumar', rating: 5, date: 'May 25, 2026', text: 'Excellent quality and transparent making charges. Highly recommended for wedding jewellery.' },
        '2': { author: 'Priya Sharma', rating: 5, date: 'May 18, 2026', text: 'Great B2B wholesale support. Timely delivery and certified gold.' },
        '3': { author: 'Amit Mehta', rating: 4, date: 'May 10, 2026', text: 'Good custom design service. Would prefer faster turnaround on orders.' }
      }
    },
    notification: {
      list: 'notifications.html',
      label: 'Notification',
      items: {
        '1': { title: 'Profile viewed 28 times today', body: 'Your public profile received 28 unique visits today. Peak traffic was between 2 PM and 4 PM.', type: 'Visitor analytics', date: '2 hours ago' },
        '2': { title: 'Homepage banner approved and live', body: 'Your banner "Flat 20% Off — Wedding Collection" has been approved and is now live on the JustGoom homepage.', type: 'Content moderation', date: 'Yesterday' },
        '3': { title: 'New inquiry from Raj Kumar', body: 'You received a new inquiry regarding a gold bulk order for 22K wedding collection.', type: 'Inquiry', date: 'May 31, 2026' },
        '4': { title: 'Article published globally', body: 'Your article "Why 22K Gold is the Smart Choice" is now published and visible to all Platinum and Gold plan visitors.', type: 'Publishing', date: 'May 28, 2026' }
      }
    }
  };

  window.JG_CRUD = CRUD;

  document.addEventListener('DOMContentLoaded', function() {
    initFormPage();
    initDetailPage();
    initDeletePage();
    initSaveButtons();
  });

  function getParams() {
    return new URLSearchParams(window.location.search);
  }

  function getModule() {
    return document.body.getAttribute('data-module') || getParams().get('module') || '';
  }

  function getItemId() {
    return getParams().get('id') || '';
  }

  function isEditMode() {
    return document.body.getAttribute('data-mode') === 'edit' || !!getItemId();
  }

  function initFormPage() {
    if (!document.body.classList.contains('user-crud-form')) return;

    var module = getModule();
    var config = CRUD[module];
    if (!config) return;

    var id = getItemId();
    var edit = isEditMode();
    var item = id && config.items[id] ? config.items[id] : null;

    document.querySelectorAll('[data-crud-field]').forEach(function(el) {
      var key = el.getAttribute('data-crud-field');
      if (!item || item[key] === undefined) return;
      if (el.type === 'checkbox') {
        el.checked = item[key] === true || item[key] === 'true' || item[key] === '1';
      } else if (el.tagName === 'SELECT') {
        Array.prototype.forEach.call(el.options, function(opt) {
          opt.selected = opt.value === String(item[key]) || opt.textContent === item[key];
        });
      } else if (el.tagName === 'TEXTAREA' || el.tagName === 'INPUT') {
        el.value = item[key];
      }
    });

    var preview = document.querySelector('[data-crud-preview]');
    if (preview && item && item.image) preview.src = item.image;

    var deleteBtn = document.querySelector('[data-crud-delete]');
    if (deleteBtn) {
      if (edit && id) {
        deleteBtn.href = 'delete.html?module=' + module + '&id=' + id + '&return=' + encodeURIComponent(config.list);
      } else {
        deleteBtn.style.display = 'none';
      }
    }

    var saveBtn = document.querySelector('[data-crud-save]');
    if (saveBtn) {
      saveBtn.addEventListener('click', function() {
        var msg = edit ? config.label + ' updated successfully!' : config.label + ' created successfully!';
        alert(msg);
        window.location.href = config.list;
      });
    }
  }

  function initDetailPage() {
    if (!document.body.classList.contains('user-crud-detail')) return;

    var module = getModule();
    var config = CRUD[module];
    var id = getItemId();
    if (!config || !id || !config.items[id]) return;

    var item = config.items[id];
    document.querySelectorAll('[data-crud-bind]').forEach(function(el) {
      var key = el.getAttribute('data-crud-bind');
      if (item[key] === undefined) return;
      if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
        el.value = item[key];
      } else {
        el.textContent = item[key];
      }
    });

    var stars = document.querySelector('[data-crud-stars]');
    if (stars && item.rating) {
      stars.textContent = '★'.repeat(item.rating) + '☆'.repeat(5 - item.rating);
    }

    var replyBlock = document.querySelector('[data-crud-reply-block]');
    if (replyBlock) {
      replyBlock.style.display = item.reply ? 'block' : 'none';
      var replyText = document.querySelector('[data-crud-bind="reply"]');
      if (replyText && item.reply) replyText.textContent = item.reply;
    }

    var replyBtn = document.querySelector('[data-crud-reply-link]');
    if (replyBtn && module === 'inquiry') {
      replyBtn.href = 'inquiry-reply.html?id=' + id;
      if (item.status === 'Replied') replyBtn.textContent = 'View Reply';
    }

    var deleteBtn = document.querySelector('[data-crud-delete]');
    if (deleteBtn) {
      deleteBtn.href = 'delete.html?module=' + module + '&id=' + id + '&return=' + encodeURIComponent(config.list);
    }

    var saveBtn = document.querySelector('[data-crud-save]');
    if (saveBtn) {
      saveBtn.addEventListener('click', function() {
        alert('Reply sent successfully!');
        window.location.href = config.list;
      });
    }
  }

  function initDeletePage() {
    if (!document.body.classList.contains('user-crud-delete')) return;

    var module = getModule();
    var id = getItemId();
    var config = CRUD[module];
    var returnUrl = getParams().get('return') || (config ? config.list : 'index.html');

    var nameEl = document.querySelector('[data-delete-name]');
    var labelEl = document.querySelector('[data-delete-label]');
    if (labelEl && config) labelEl.textContent = config.label;

    if (nameEl && config && config.items[id]) {
      var item = config.items[id];
      nameEl.textContent = item.title || item.name || item.subject || item.from || item.author || ('Item #' + id);
    }

    var cancelBtn = document.querySelector('[data-delete-cancel]');
    if (cancelBtn) cancelBtn.href = returnUrl;

    var confirmBtn = document.querySelector('[data-delete-confirm]');
    if (confirmBtn) {
      confirmBtn.addEventListener('click', function() {
        alert((config ? config.label : 'Item') + ' deleted successfully.');
        window.location.href = returnUrl;
      });
    }
  }

  function initSaveButtons() {
    document.querySelectorAll('[data-crud-draft]').forEach(function(btn) {
      btn.addEventListener('click', function() {
        alert('Draft saved successfully!');
        var module = getModule();
        if (CRUD[module]) window.location.href = CRUD[module].list;
      });
    });
  }
})();
