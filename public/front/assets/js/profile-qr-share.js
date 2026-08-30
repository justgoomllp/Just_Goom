(function () {
  var qrFlyer = document.getElementById('profileQrFlyer');
  if (!qrFlyer) return;

  var brandedQrDataUrl = null;
  var brandedQrBlob = null;

  var NAVY = '#003366';
  var PRIMARY = '#1A428A';
  var ACCENT = '#F7941D';
  var SOFT = '#F4F7FB';

  function loadImage(src) {
    return new Promise(function (resolve) {
      if (!src) return resolve(null);
      var img = new Image();
      var isBlobOrData = src.indexOf('blob:') === 0 || src.indexOf('data:') === 0;
      var isSameOrigin = src.indexOf(window.location.origin) === 0 || src.charAt(0) === '/';
      if (!isBlobOrData && !isSameOrigin) {
        img.crossOrigin = 'anonymous';
      }
      img.onload = function () { resolve(img); };
      img.onerror = function () { resolve(null); };
      img.src = src;
    });
  }

  function roundedRect(ctx, x, y, w, h, r) {
    var radius = Math.min(r, w / 2, h / 2);
    ctx.beginPath();
    ctx.moveTo(x + radius, y);
    ctx.arcTo(x + w, y, x + w, y + h, radius);
    ctx.arcTo(x + w, y + h, x, y + h, radius);
    ctx.arcTo(x, y + h, x, y, radius);
    ctx.arcTo(x, y, x + w, y, radius);
    ctx.closePath();
  }

  function drawImageContain(ctx, img, x, y, w, h, pad) {
    pad = pad || 0;
    var boxW = w - pad * 2;
    var boxH = h - pad * 2;
    var iw = img.naturalWidth || img.width;
    var ih = img.naturalHeight || img.height;
    if (!iw || !ih) return;
    var scale = Math.min(boxW / iw, boxH / ih);
    var dw = iw * scale;
    var dh = ih * scale;
    ctx.drawImage(img, x + pad + (boxW - dw) / 2, y + pad + (boxH - dh) / 2, dw, dh);
  }

  function wrapText(ctx, text, maxWidth, maxLines) {
    var words = String(text || '').split(/\s+/);
    var lines = [];
    var line = '';
    var limit = maxLines || 2;
    words.forEach(function (word) {
      var test = line ? line + ' ' + word : word;
      if (ctx.measureText(test).width > maxWidth && line) {
        lines.push(line);
        line = word;
      } else {
        line = test;
      }
    });
    if (line) lines.push(line);
    return lines.slice(0, limit);
  }

  function drawWrappedCentered(ctx, text, centerX, y, maxWidth, lineHeight, maxLines) {
    var lines = wrapText(ctx, text, maxWidth, maxLines);
    lines.forEach(function (line, i) {
      ctx.fillText(line, centerX, y + (i * lineHeight));
    });
    return lines.length;
  }

  /** Ensure QR PNG has opaque white background (avoids black share previews). */
  function normalizeQrBlob(blob) {
    return new Promise(function (resolve, reject) {
      var url = URL.createObjectURL(blob);
      var img = new Image();
      img.onload = function () {
        var size = Math.max(img.naturalWidth || 400, img.naturalHeight || 400);
        var canvas = document.createElement('canvas');
        canvas.width = size;
        canvas.height = size;
        var ctx = canvas.getContext('2d');
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, size, size);
        ctx.drawImage(img, 0, 0, size, size);
        URL.revokeObjectURL(url);
        canvas.toBlob(function (out) {
          if (!out) reject(new Error('normalize failed'));
          else resolve(out);
        }, 'image/png');
      };
      img.onerror = function () {
        URL.revokeObjectURL(url);
        reject(new Error('qr image load failed'));
      };
      img.src = url;
    });
  }

  function showQrInMount(objectUrl) {
    var mount = document.getElementById('profileQrMount');
    if (!mount) return;
    mount.innerHTML = '';
    var img = document.createElement('img');
    img.src = objectUrl;
    img.alt = 'Profile QR Code';
    img.width = 140;
    img.height = 140;
    img.className = 'profile-qr-img';
    mount.appendChild(img);
  }

  function ensureBrandedQr() {
    if (brandedQrDataUrl && brandedQrBlob) {
      return Promise.resolve({ dataUrl: brandedQrDataUrl, blob: brandedQrBlob });
    }

    var qrUrl = qrFlyer.getAttribute('data-qr-url') || '';
    if (!qrUrl) return Promise.reject(new Error('missing qr url'));

    return fetch(qrUrl, { cache: 'no-store' })
      .then(function (res) {
        if (!res.ok) throw new Error('qr fetch failed');
        return res.blob();
      })
      .then(function (blob) {
        return normalizeQrBlob(blob);
      })
      .then(function (blob) {
        brandedQrBlob = blob;
        brandedQrDataUrl = URL.createObjectURL(blob);
        showQrInMount(brandedQrDataUrl);
        return { dataUrl: brandedQrDataUrl, blob: brandedQrBlob };
      });
  }

  function canvasToBlob(canvas) {
    return new Promise(function (resolve) {
      if (canvas.toBlob) {
        canvas.toBlob(function (blob) { resolve(blob); }, 'image/png');
        return;
      }
      var dataUrl = canvas.toDataURL('image/png');
      var bin = atob(dataUrl.split(',')[1]);
      var arr = new Uint8Array(bin.length);
      for (var i = 0; i < bin.length; i++) arr[i] = bin.charCodeAt(i);
      resolve(new Blob([arr], { type: 'image/png' }));
    });
  }

  function buildProfileFlyer(qrImg, logoImg) {
    var company = qrFlyer.getAttribute('data-company') || 'Company';
    var initials = qrFlyer.getAttribute('data-initials') || 'JG';
    var category = (qrFlyer.getAttribute('data-category') || 'Company Profile').toUpperCase();
    var profileUrl = qrFlyer.getAttribute('data-profile-url') || '';

    var width = 780;
    var height = 1180;
    var canvas = document.createElement('canvas');
    canvas.width = width;
    canvas.height = height;
    var ctx = canvas.getContext('2d');
    var cx = width / 2;

    var bg = ctx.createLinearGradient(0, 0, width, height);
    bg.addColorStop(0, '#0d2b55');
    bg.addColorStop(0.45, PRIMARY);
    bg.addColorStop(1, '#2a5cb8');
    ctx.fillStyle = bg;
    ctx.fillRect(0, 0, width, height);

    ctx.fillStyle = 'rgba(247,148,29,0.18)';
    ctx.beginPath();
    ctx.arc(width - 40, 80, 120, 0, Math.PI * 2);
    ctx.fill();
    ctx.fillStyle = 'rgba(255,255,255,0.08)';
    ctx.beginPath();
    ctx.arc(60, height - 120, 160, 0, Math.PI * 2);
    ctx.fill();

    var cardX = 36;
    var cardY = 36;
    var cardW = width - 72;
    var cardH = height - 72;
    ctx.fillStyle = '#ffffff';
    roundedRect(ctx, cardX, cardY, cardW, cardH, 28);
    ctx.fill();

    ctx.fillStyle = ACCENT;
    roundedRect(ctx, cardX, cardY, cardW, 10, 0);
    ctx.fill();
    ctx.fillStyle = '#ffffff';
    roundedRect(ctx, cardX, cardY + 6, cardW, 24, 0);
    ctx.fill();

    var y = cardY + 48;

    ctx.fillStyle = NAVY;
    roundedRect(ctx, cx - 86, y, 172, 40, 20);
    ctx.fill();
    ctx.fillStyle = '#fff';
    ctx.font = 'bold 16px Arial, sans-serif';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText('DIGITAL PROFILE', cx, y + 20);

    [cx - 112, cx - 124, cx - 136, cx + 112, cx + 124, cx + 136].forEach(function (dx, i) {
      ctx.beginPath();
      ctx.fillStyle = ACCENT;
      ctx.globalAlpha = i % 3 === 0 ? 1 : (i % 3 === 1 ? 0.7 : 0.4);
      ctx.arc(dx, y + 20, 4, 0, Math.PI * 2);
      ctx.fill();
    });
    ctx.globalAlpha = 1;
    y += 68;

    var logoOuter = 128;
    var logoInner = 108;
    ctx.fillStyle = 'rgba(0,51,102,0.10)';
    ctx.beginPath();
    ctx.arc(cx + 2, y + logoOuter / 2 + 4, logoOuter / 2, 0, Math.PI * 2);
    ctx.fill();
    ctx.fillStyle = '#fff';
    ctx.beginPath();
    ctx.arc(cx, y + logoOuter / 2, logoOuter / 2, 0, Math.PI * 2);
    ctx.fill();
    ctx.strokeStyle = ACCENT;
    ctx.lineWidth = 4;
    ctx.beginPath();
    ctx.arc(cx, y + logoOuter / 2, logoOuter / 2 - 2, 0, Math.PI * 2);
    ctx.stroke();
    ctx.fillStyle = SOFT;
    ctx.beginPath();
    ctx.arc(cx, y + logoOuter / 2, logoInner / 2, 0, Math.PI * 2);
    ctx.fill();

    if (logoImg) {
      ctx.save();
      ctx.beginPath();
      ctx.arc(cx, y + logoOuter / 2, logoInner / 2 - 2, 0, Math.PI * 2);
      ctx.closePath();
      ctx.clip();
      var box = logoInner - 8;
      drawImageContain(ctx, logoImg, cx - box / 2, y + logoOuter / 2 - box / 2, box, box, 8);
      ctx.restore();
    } else {
      ctx.fillStyle = PRIMARY;
      ctx.font = 'bold 36px Arial, sans-serif';
      ctx.textAlign = 'center';
      ctx.textBaseline = 'middle';
      ctx.fillText(initials, cx, y + logoOuter / 2);
    }
    y += logoOuter + 28;

    ctx.fillStyle = NAVY;
    ctx.font = 'bold 40px Arial, sans-serif';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'alphabetic';
    var nameLines = drawWrappedCentered(ctx, company, cx, y, cardW - 80, 46, 2);
    y += nameLines * 46 + 12;

    ctx.font = 'bold 14px Arial, sans-serif';
    var catW = Math.min(ctx.measureText(category).width + 36, cardW - 120);
    ctx.fillStyle = SOFT;
    roundedRect(ctx, cx - catW / 2, y - 18, catW, 32, 16);
    ctx.fill();
    ctx.fillStyle = PRIMARY;
    ctx.textBaseline = 'middle';
    ctx.fillText(category, cx, y - 2);
    y += 36;

    ctx.fillStyle = ACCENT;
    roundedRect(ctx, cx - 120, y, 240, 46, 23);
    ctx.fill();
    ctx.fillStyle = '#fff';
    ctx.font = 'bold 18px Arial, sans-serif';
    ctx.textBaseline = 'middle';
    ctx.fillText('SCAN TO CONNECT', cx, y + 23);
    y += 64;

    ctx.fillStyle = '#5a6a7a';
    ctx.font = '600 13px Arial, sans-serif';
    ctx.textBaseline = 'alphabetic';
    ctx.fillText('SCAN THE QR CODE TO VIEW THIS PROFILE', cx, y);
    y += 28;

    var qrSize = 320;
    var qrPad = 18;
    var frameSize = qrSize + qrPad * 2;
    var frameX = cx - frameSize / 2;
    ctx.fillStyle = SOFT;
    roundedRect(ctx, frameX - 6, y - 6, frameSize + 12, frameSize + 12, 22);
    ctx.fill();
    ctx.fillStyle = '#fff';
    roundedRect(ctx, frameX, y, frameSize, frameSize, 18);
    ctx.fill();
    ctx.strokeStyle = PRIMARY;
    ctx.lineWidth = 3;
    roundedRect(ctx, frameX, y, frameSize, frameSize, 18);
    ctx.stroke();
    ctx.drawImage(qrImg, frameX + qrPad, y + qrPad, qrSize, qrSize);
    y += frameSize + 22;

    ctx.fillStyle = NAVY;
    ctx.font = '600 14px Arial, sans-serif';
    ctx.textAlign = 'center';
    var shortUrl = profileUrl.replace(/^https?:\/\//i, '');
    wrapText(ctx, shortUrl, cardW - 100, 2).forEach(function (line, i) {
      ctx.fillText(line, cx, y + i * 20);
    });
    y += 40;

    var features = [
      { t: 'Verified Profile', c: PRIMARY },
      { t: 'Instant Access', c: ACCENT },
      { t: 'Easy Share', c: '#14b8a6' }
    ];
    var featGap = 14;
    var featW = (cardW - 80 - featGap * 2) / 3;
    features.forEach(function (feat, i) {
      var fx = cardX + 40 + i * (featW + featGap);
      ctx.fillStyle = SOFT;
      roundedRect(ctx, fx, y, featW, 56, 14);
      ctx.fill();
      ctx.fillStyle = feat.c;
      ctx.beginPath();
      ctx.arc(fx + 22, y + 28, 8, 0, Math.PI * 2);
      ctx.fill();
      ctx.fillStyle = NAVY;
      ctx.font = 'bold 12px Arial, sans-serif';
      ctx.textAlign = 'left';
      ctx.textBaseline = 'middle';
      ctx.fillText(feat.t, fx + 38, y + 28);
    });
    y += 78;

    var footerH = 100;
    var footerY = Math.min(y, cardY + cardH - footerH - 28);
    var footerGrad = ctx.createLinearGradient(cardX + 28, footerY, cardX + cardW - 28, footerY);
    footerGrad.addColorStop(0, NAVY);
    footerGrad.addColorStop(1, PRIMARY);
    ctx.fillStyle = footerGrad;
    roundedRect(ctx, cardX + 28, footerY, cardW - 56, footerH, 18);
    ctx.fill();
    ctx.fillStyle = ACCENT;
    roundedRect(ctx, cardX + 28, footerY, 8, footerH, 4);
    ctx.fill();
    ctx.fillStyle = 'rgba(255,255,255,0.7)';
    ctx.font = 'bold 12px Arial, sans-serif';
    ctx.textAlign = 'left';
    ctx.textBaseline = 'alphabetic';
    ctx.fillText('PROFILE URL', cardX + 52, footerY + 30);
    ctx.fillStyle = '#ffffff';
    ctx.font = '600 15px Arial, sans-serif';
    wrapText(ctx, profileUrl, cardW - 120, 2).forEach(function (line, i) {
      ctx.fillText(line, cardX + 52, footerY + 56 + i * 20);
    });

    return canvas;
  }

  function triggerDownload(blob, filename) {
    var link = document.createElement('a');
    link.download = filename;
    link.href = URL.createObjectURL(blob);
    document.body.appendChild(link);
    link.click();
    link.remove();
    setTimeout(function () { URL.revokeObjectURL(link.href); }, 1500);
  }

  function downloadFlyerCard(filename) {
    var logoUrl = qrFlyer.getAttribute('data-logo') || '';
    var heroLogo = document.querySelector('.profile-hero-logo img');
    if (heroLogo && heroLogo.src) logoUrl = heroLogo.src;

    return Promise.all([
      ensureBrandedQr(),
      loadImage(logoUrl)
    ]).then(function (results) {
      var branded = results[0];
      var logoImg = results[1];
      return loadImage(branded.dataUrl).then(function (qrImg) {
        if (!qrImg) throw new Error('QR image missing');
        return canvasToBlob(buildProfileFlyer(qrImg, logoImg));
      });
    }).then(function (blob) {
      triggerDownload(blob, filename || 'profile-qr-card.png');
      return blob;
    });
  }

  window.JustGoomProfileQr = {
    ensureBrandedQr: ensureBrandedQr,
    downloadFlyerCard: downloadFlyerCard
  };

  ensureBrandedQr().catch(function (err) {
    console.warn('Profile QR failed.', err);
  });

  var shareBtn = document.getElementById('shareProfileBtn');
  if (shareBtn) {
    var shareLabel = shareBtn.querySelector('.btn-share-label');
    shareBtn.addEventListener('click', function () {
      var url = shareBtn.getAttribute('data-url');
      var company = qrFlyer.getAttribute('data-company') || 'Just Goom Profile';
      var prev = shareLabel ? shareLabel.textContent : 'Share Profile';

      function markCopied() {
        if (!shareLabel) return;
        shareLabel.textContent = 'Link Copied';
        setTimeout(function () { shareLabel.textContent = prev; }, 1800);
      }

      ensureBrandedQr().then(function (branded) {
        var file = new File([branded.blob], 'justgoom-profile-qr.png', { type: 'image/png' });
        var shareData = {
          title: company + ' | Just Goom',
          text: 'Scan QR or open my Just Goom profile',
          url: url
        };

        if (navigator.canShare && navigator.canShare({ files: [file] })) {
          shareData.files = [file];
          return navigator.share(shareData);
        }
        if (navigator.share) {
          return navigator.share(shareData);
        }
        if (navigator.clipboard && navigator.clipboard.writeText) {
          return navigator.clipboard.writeText(url).then(markCopied);
        }
        window.prompt('Copy profile link:', url);
      }).catch(function () {
        if (navigator.clipboard && navigator.clipboard.writeText) {
          navigator.clipboard.writeText(url).then(markCopied);
          return;
        }
        window.prompt('Copy profile link:', url);
      });
    });
  }

  var downloadQrBtn = document.getElementById('downloadProfileQrBtn');
  if (downloadQrBtn) {
    downloadQrBtn.addEventListener('click', function () {
      var company = (qrFlyer.getAttribute('data-company') || 'profile')
        .replace(/[^\w\-]+/g, '-')
        .toLowerCase();
      var prev = downloadQrBtn.textContent;
      downloadQrBtn.disabled = true;
      downloadQrBtn.textContent = 'Preparing…';

      downloadFlyerCard(company + '-profile-qr.png')
        .catch(function () {
          alert('Unable to download QR card. Please try again.');
        })
        .finally(function () {
          downloadQrBtn.disabled = false;
          downloadQrBtn.textContent = prev;
        });
    });
  }
})();
