function EbpUtil () {}
EbpUtil.isTrue = function (bool) {
  return bool === 'true' || bool === '1' || bool === 1 || bool === true;
}


EbpUtil.validateEmail = function (email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

  return re.test(email);
}

EbpUtil.getFormattedNumber = function (x, priceDecPoint, priceThousandsSep, currency) {
  if (x == "") return 0;

  return parseFloat(x.replace(priceThousandsSep, "").replace(priceDecPoint, ".").replace(currency, ""));
}

EbpUtil.formatPrice = function (x, priceDecPoint, priceThousandsSep, priceDecLength) {
  x = x.toFixed(priceDecLength);
  x = x.toString();

  var decPart = x.substring(x.indexOf(".") + 1, x.length);

  if(priceDecLength == 0) {
    decPart = 0;
  }

  if(priceDecLength > 0) {
    x = x.substring(0, x.indexOf("."));
  }

  x = x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, priceThousandsSep);

  if(priceDecLength > 0) {
    x = x + priceDecPoint + decPart;
  }

  return x;
}
EbpUtil.formatPriceWithCurrency = function (x, priceDecPoint, priceThousandsSep, priceDecLength, currencyBefore, currency) {
  x  = this.formatPrice(x, priceDecPoint, priceThousandsSep, priceDecLength);
  return (currencyBefore) ? currency + "" + x : x + " " + currency;
}

