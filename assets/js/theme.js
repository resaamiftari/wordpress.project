(function () {
  var toggle = document.querySelector('.menu-toggle');
  var nav = document.querySelector('.site-nav');
  var bagPanel = document.querySelector('.shop-bag');
  var bagItems = document.querySelector('[data-bag-items]');
  var bagCount = document.querySelector('[data-bag-count]');
  var bagSummaryCount = document.querySelector('[data-bag-summary-count]');
  var storageKey = 'secretFlowerShopBag';
  var memoryBag = [];

  function storageAvailable() {
    try {
      var testKey = '__sfs_test__';
      window.localStorage.setItem(testKey, '1');
      window.localStorage.removeItem(testKey);
      return true;
    } catch (error) {
      return false;
    }
  }

  function readBag() {
    var raw;

    if (storageAvailable()) {
      raw = window.localStorage.getItem(storageKey);
      if (!raw) {
        return [];
      }

      try {
        return JSON.parse(raw) || [];
      } catch (error) {
        return [];
      }
    }

    return memoryBag.slice();
  }

  function writeBag(items) {
    if (storageAvailable()) {
      window.localStorage.setItem(storageKey, JSON.stringify(items));
      return;
    }

    memoryBag = items.slice();
  }

  function getItemCount(items) {
    var total = 0;
    var i;

    for (i = 0; i < items.length; i += 1) {
      total += items[i].qty || 0;
    }

    return total;
  }

  function escapeHtml(value) {
    return String(value || '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  function createPlaceholderImage(title) {
    var letter = String(title || 'F').trim().charAt(0).toUpperCase();
    var svg = [
      '<svg xmlns="http://www.w3.org/2000/svg" width="160" height="160" viewBox="0 0 160 160">',
      '<defs><linearGradient id="g" x1="0" x2="1" y1="0" y2="1"><stop stop-color="#ffe8f0"/><stop offset="1" stop-color="#e8f6ea"/></linearGradient></defs>',
      '<rect width="160" height="160" rx="24" fill="url(#g)"/>',
      '<circle cx="80" cy="72" r="34" fill="#ffffff" opacity="0.88"/>',
      '<circle cx="80" cy="72" r="22" fill="#d991ae" opacity="0.92"/>',
      '<text x="80" y="90" font-family="Georgia, serif" font-size="34" text-anchor="middle" fill="#ffffff">',
      letter,
      '</text>',
      '</svg>'
    ].join('');

    return 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg);
  }

  function openBag() {
    if (bagPanel) {
      bagPanel.classList.add('is-open');
    }
  }

  function closeBag() {
    if (bagPanel) {
      bagPanel.classList.remove('is-open');
    }
  }

  function renderBag() {
    var items = readBag();
    var count = getItemCount(items);
    var html = '';
    var i;
    var item;
    var imageSrc;
    var titleLink;

    if (bagCount) {
      bagCount.textContent = String(count);
    }

    if (bagSummaryCount) {
      bagSummaryCount.textContent = String(count);
    }

    if (!bagItems) {
      return;
    }

    if (!items.length) {
      bagItems.innerHTML = '<p class="shop-bag__empty">' + escapeHtml((window.SecretFlowerShopBag && window.SecretFlowerShopBag.emptyText) ? window.SecretFlowerShopBag.emptyText : 'Your bag is empty.') + ' Start adding flowers to watch them here.</p>';
      return;
    }

    for (i = 0; i < items.length; i += 1) {
      item = items[i];
      imageSrc = item.image || createPlaceholderImage(item.title);
      titleLink = item.url ? '<a href="' + escapeHtml(item.url) + '">' + escapeHtml(item.title) + '</a>' : escapeHtml(item.title);

      html += '' +
        '<div class="shop-bag__item">' +
          '<img class="shop-bag__thumb" src="' + imageSrc + '" alt="' + escapeHtml(item.title) + '">' +
          '<div>' +
            '<h3>' + titleLink + '</h3>' +
            '<p>' + escapeHtml(item.price) + ' &times; ' + String(item.qty || 1) + '</p>' +
          '</div>' +
          '<button type="button" class="shop-bag__remove" data-remove-index="' + i + '" aria-label="Remove ' + escapeHtml(item.title) + '">&times;</button>' +
        '</div>';
    }

    bagItems.innerHTML = html;
  }

  function addItemFromButton(button) {
    var item = {
      title: button.getAttribute('data-title') || 'Flower',
      price: button.getAttribute('data-price') || '',
      image: button.getAttribute('data-image') || '',
      url: button.getAttribute('data-url') || '#',
      qty: 1
    };
    var items = readBag();
    var existing = null;
    var i;

    for (i = 0; i < items.length; i += 1) {
      if (items[i].title === item.title) {
        existing = items[i];
        break;
      }
    }

    if (existing) {
      existing.qty = (existing.qty || 1) + 1;
      items.splice(i, 1);
      items.unshift(existing);
    } else {
      items.unshift(item);
    }

    writeBag(items);
    renderBag();
    openBag();
  }

  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var isOpen = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
  }

  document.addEventListener('click', function (event) {
    var target = event.target;
    var addButton = target.closest ? target.closest('[data-bag-add]') : null;
    var removeButton = target.closest ? target.closest('[data-remove-index]') : null;
    var bagLink = target.closest ? target.closest('[href="#shop-bag"]') : null;
    var clearButton = target.closest ? target.closest('[data-bag-clear]') : null;
    var closeButton = target.closest ? target.closest('[data-bag-close]') : null;

    if (addButton) {
      event.preventDefault();
      addItemFromButton(addButton);
      addButton.textContent = 'Added';

      window.setTimeout(function () {
        addButton.textContent = 'Add to Bag';
      }, 1200);
      return;
    }

    if (removeButton) {
      event.preventDefault();
      var index = Number(removeButton.getAttribute('data-remove-index'));
      var items = readBag();
      if (index >= 0 && index < items.length) {
        items.splice(index, 1);
        writeBag(items);
        renderBag();
      }
      return;
    }

    if (bagLink) {
      event.preventDefault();
      openBag();
      renderBag();
      return;
    }

    if (clearButton) {
      event.preventDefault();
      writeBag([]);
      renderBag();
      openBag();
      return;
    }

    if (closeButton) {
      event.preventDefault();
      closeBag();
    }
  });

  renderBag();
})();
