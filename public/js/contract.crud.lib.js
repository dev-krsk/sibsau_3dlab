function find(el, selector) {
  var completeList = [],
      result;

  result = el.querySelectorAll(selector);
  result = Array.prototype.slice.call(result); //convert NodeList to array
  completeList = completeList.concat(result); //concat the result

  //return the collected elements as new result set
  return completeList;
}

function findByOne(el, selector) {
  var completeList = [],
      result;

  result = el.querySelectorAll(selector);
  result = Array.prototype.slice.call(result); //convert NodeList to array
  completeList = completeList.concat(result); //concat the result

  //return the collected elements as new result set
  return completeList[0];
}

function matches(el, selector) {
  var matchesFn;

  // find vendor prefix
  ['matches', 'webkitMatchesSelector', 'mozMatchesSelector',
    'msMatchesSelector', 'oMatchesSelector'].some(function (fn) {
    if (typeof document.body[fn] == 'function') {
      matchesFn = fn;
      return true;
    }
    return false;
  })

  return el[matchesFn](selector);
}

function closest(el, selector) {
  var parent;

  // traverse parents
  while (el) {
    parent = el.parentElement;
    if (parent && matches(parent, selector)) {
      return parent;
    }
    el = parent;
  }

  return null;
}

function changeLabWork(el) {
  var context = el
  var parent = closest(el, '.accordion-item')

  if (!parent) {
    return;
  }

  find(parent, '.accordion-button').some(function (el) {
    el.innerHTML = el.innerHTML.replace(el.textContent.trim(),
        context.options[context.selectedIndex].text);
  })
}

function updateDate(el) {
  var parent;

  if (el === undefined) {
    parent = findByOne(document, 'form[name="Contract"]')
  } else {
    parent = closest(el, 'form')
  }

  console.log(parent);

  if (!parent) {
    return;
  }

  var createdAt = findByOne(parent,
      'input[type=date][name="Contract[createdAt]"]').value;
  var removedAt = findByOne(parent,
      'input[type=date][name="Contract[removedAt]"]').value;

  find(parent, '.accordion-item').some(function (el) {
    var inherit = findByOne(el, 'input[type=checkbox]').checked;

    find(el, 'input[type=date][name*=createdAt]').some(function (el) {
      if (inherit) {
        el.value = createdAt;
        el.disabled = true;
      } else {
        el.disabled = false;
      }
    })

    find(el, 'input[type=date][name*=removedAt]').some(function (el) {
      if (inherit) {
        el.value = removedAt;
        el.disabled = true;
      } else {
        el.disabled = false;
      }
    })
  })
}

window.onload = function () {
  updateDate()
};

