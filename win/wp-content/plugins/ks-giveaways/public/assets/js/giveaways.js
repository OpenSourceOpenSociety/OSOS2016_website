function ks_giveaways_md5cycle(x, k) {
  var a = x[0], b = x[1], c = x[2], d = x[3];

  a = ks_giveaways_ff(a, b, c, d, k[0], 7, -680876936);
  d = ks_giveaways_ff(d, a, b, c, k[1], 12, -389564586);
  c = ks_giveaways_ff(c, d, a, b, k[2], 17,  606105819);
  b = ks_giveaways_ff(b, c, d, a, k[3], 22, -1044525330);
  a = ks_giveaways_ff(a, b, c, d, k[4], 7, -176418897);
  d = ks_giveaways_ff(d, a, b, c, k[5], 12,  1200080426);
  c = ks_giveaways_ff(c, d, a, b, k[6], 17, -1473231341);
  b = ks_giveaways_ff(b, c, d, a, k[7], 22, -45705983);
  a = ks_giveaways_ff(a, b, c, d, k[8], 7,  1770035416);
  d = ks_giveaways_ff(d, a, b, c, k[9], 12, -1958414417);
  c = ks_giveaways_ff(c, d, a, b, k[10], 17, -42063);
  b = ks_giveaways_ff(b, c, d, a, k[11], 22, -1990404162);
  a = ks_giveaways_ff(a, b, c, d, k[12], 7,  1804603682);
  d = ks_giveaways_ff(d, a, b, c, k[13], 12, -40341101);
  c = ks_giveaways_ff(c, d, a, b, k[14], 17, -1502002290);
  b = ks_giveaways_ff(b, c, d, a, k[15], 22,  1236535329);

  a = ks_giveaways_gg(a, b, c, d, k[1], 5, -165796510);
  d = ks_giveaways_gg(d, a, b, c, k[6], 9, -1069501632);
  c = ks_giveaways_gg(c, d, a, b, k[11], 14,  643717713);
  b = ks_giveaways_gg(b, c, d, a, k[0], 20, -373897302);
  a = ks_giveaways_gg(a, b, c, d, k[5], 5, -701558691);
  d = ks_giveaways_gg(d, a, b, c, k[10], 9,  38016083);
  c = ks_giveaways_gg(c, d, a, b, k[15], 14, -660478335);
  b = ks_giveaways_gg(b, c, d, a, k[4], 20, -405537848);
  a = ks_giveaways_gg(a, b, c, d, k[9], 5,  568446438);
  d = ks_giveaways_gg(d, a, b, c, k[14], 9, -1019803690);
  c = ks_giveaways_gg(c, d, a, b, k[3], 14, -187363961);
  b = ks_giveaways_gg(b, c, d, a, k[8], 20,  1163531501);
  a = ks_giveaways_gg(a, b, c, d, k[13], 5, -1444681467);
  d = ks_giveaways_gg(d, a, b, c, k[2], 9, -51403784);
  c = ks_giveaways_gg(c, d, a, b, k[7], 14,  1735328473);
  b = ks_giveaways_gg(b, c, d, a, k[12], 20, -1926607734);

  a = ks_giveaways_hh(a, b, c, d, k[5], 4, -378558);
  d = ks_giveaways_hh(d, a, b, c, k[8], 11, -2022574463);
  c = ks_giveaways_hh(c, d, a, b, k[11], 16,  1839030562);
  b = ks_giveaways_hh(b, c, d, a, k[14], 23, -35309556);
  a = ks_giveaways_hh(a, b, c, d, k[1], 4, -1530992060);
  d = ks_giveaways_hh(d, a, b, c, k[4], 11,  1272893353);
  c = ks_giveaways_hh(c, d, a, b, k[7], 16, -155497632);
  b = ks_giveaways_hh(b, c, d, a, k[10], 23, -1094730640);
  a = ks_giveaways_hh(a, b, c, d, k[13], 4,  681279174);
  d = ks_giveaways_hh(d, a, b, c, k[0], 11, -358537222);
  c = ks_giveaways_hh(c, d, a, b, k[3], 16, -722521979);
  b = ks_giveaways_hh(b, c, d, a, k[6], 23,  76029189);
  a = ks_giveaways_hh(a, b, c, d, k[9], 4, -640364487);
  d = ks_giveaways_hh(d, a, b, c, k[12], 11, -421815835);
  c = ks_giveaways_hh(c, d, a, b, k[15], 16,  530742520);
  b = ks_giveaways_hh(b, c, d, a, k[2], 23, -995338651);

  a = ks_giveaways_ii(a, b, c, d, k[0], 6, -198630844);
  d = ks_giveaways_ii(d, a, b, c, k[7], 10,  1126891415);
  c = ks_giveaways_ii(c, d, a, b, k[14], 15, -1416354905);
  b = ks_giveaways_ii(b, c, d, a, k[5], 21, -57434055);
  a = ks_giveaways_ii(a, b, c, d, k[12], 6,  1700485571);
  d = ks_giveaways_ii(d, a, b, c, k[3], 10, -1894986606);
  c = ks_giveaways_ii(c, d, a, b, k[10], 15, -1051523);
  b = ks_giveaways_ii(b, c, d, a, k[1], 21, -2054922799);
  a = ks_giveaways_ii(a, b, c, d, k[8], 6,  1873313359);
  d = ks_giveaways_ii(d, a, b, c, k[15], 10, -30611744);
  c = ks_giveaways_ii(c, d, a, b, k[6], 15, -1560198380);
  b = ks_giveaways_ii(b, c, d, a, k[13], 21,  1309151649);
  a = ks_giveaways_ii(a, b, c, d, k[4], 6, -145523070);
  d = ks_giveaways_ii(d, a, b, c, k[11], 10, -1120210379);
  c = ks_giveaways_ii(c, d, a, b, k[2], 15,  718787259);
  b = ks_giveaways_ii(b, c, d, a, k[9], 21, -343485551);

  x[0] = ks_giveaways_add32(a, x[0]);
  x[1] = ks_giveaways_add32(b, x[1]);
  x[2] = ks_giveaways_add32(c, x[2]);
  x[3] = ks_giveaways_add32(d, x[3]);
}

function ks_giveaways_cmn(q, a, b, x, s, t) {
  a = ks_giveaways_add32(ks_giveaways_add32(a, q), ks_giveaways_add32(x, t));
  return ks_giveaways_add32((a << s) | (a >>> (32 - s)), b);
}

function ks_giveaways_ff(a, b, c, d, x, s, t) {
  return ks_giveaways_cmn((b & c) | ((~b) & d), a, b, x, s, t);
}

function ks_giveaways_gg(a, b, c, d, x, s, t) {
  return ks_giveaways_cmn((b & d) | (c & (~d)), a, b, x, s, t);
}

function ks_giveaways_hh(a, b, c, d, x, s, t) {
  return ks_giveaways_cmn(b ^ c ^ d, a, b, x, s, t);
}

function ks_giveaways_ii(a, b, c, d, x, s, t) {
  return ks_giveaways_cmn(c ^ (b | (~d)), a, b, x, s, t);
}

function ks_giveaways_md51(s) {
  // Converts the string to UTF-8 "bytes" when necessary
  s = unescape(encodeURIComponent(s));

  var txt = '';
  var n = s.length,
  state = [1732584193, -271733879, -1732584194, 271733878], i;
  for (i=64; i<=s.length; i+=64) {
    ks_giveaways_md5cycle(state, ks_giveaways_md5blk(s.substring(i-64, i)));
  }
  s = s.substring(i-64);
  var tail = [0,0,0,0, 0,0,0,0, 0,0,0,0, 0,0,0,0];
  for (i=0; i<s.length; i++)
    tail[i>>2] |= s.charCodeAt(i) << ((i%4) << 3);
    tail[i>>2] |= 0x80 << ((i%4) << 3);
    if (i > 55) {
      ks_giveaways_md5cycle(state, tail);
      for (i=0; i<16; i++) tail[i] = 0;
    }
  tail[14] = n*8;
  ks_giveaways_md5cycle(state, tail);
  return state;
}

/* there needs to be support for Unicode here,
 * unless we pretend that we can redefine the MD-5
 * algorithm for multi-byte characters (perhaps
 * by adding every four 16-bit characters and
 * shortening the sum to 32 bits). Otherwise
 * I suggest performing MD-5 as if every character
 * was two bytes--e.g., 0040 0025 = @%--but then
 * how will an ordinary MD-5 sum be matched?
 * There is no way to standardize text to something
 * like UTF-8 before transformation; speed cost is
 * utterly prohibitive. The JavaScript standard
 * itself needs to look at this: it should start
 * providing access to strings as preformed UTF-8
 * 8-bit unsigned value arrays.
 */
function ks_giveaways_md5blk(s) { /* I figured global was faster.   */
  var md5blks = [], i; /* Andy King said do it this way. */
  for (i=0; i<64; i+=4) {
    md5blks[i>>2] = s.charCodeAt(i)
    + (s.charCodeAt(i+1) << 8)
    + (s.charCodeAt(i+2) << 16)
    + (s.charCodeAt(i+3) << 24);
  }
  return md5blks;
}

function ks_giveaways_rhex(n)
{
  var hex_chr = '0123456789abcdef'.split('');
  var s='', j=0;

  for(; j<4; j++)
    s += hex_chr[(n >> (j * 8 + 4)) & 0x0F] + hex_chr[(n >> (j * 8)) & 0x0F];

  return s;
}

function ks_giveaways_hex(x) {
  for (var i=0; i<x.length; i++)
    x[i] = ks_giveaways_rhex(x[i]);

  return x.join('');
}

function ks_giveaways_md5(s) {
  return ks_giveaways_hex(ks_giveaways_md51(s));
}

function ks_giveaways_add32(x, y) {
  var lsw = (x & 0xFFFF) + (y & 0xFFFF),
  msw = (x >> 16) + (y >> 16) + (lsw >> 16);
  return (msw << 16) | (lsw & 0xFFFF);
}

jQuery(document).ready(function($) {
  $(document).foundation();

  $('#giveaways_answer').change(function() {
    var answer = $('#giveaways_answer').val();

    if (answer == 'wrong') {
      $('.contest-question').addClass('error');
      $('.contest-question .error').show();
    } else if (answer == 'right') {
      $('.contest-question').hide();
      $('.contest-entry').show();
    }
  });

  $('#giveaways_form').on('submit', function() {
    var email = $('#giveaways_email').val();
    var answer = $('#giveaways_answer option:selected').text();

    if (email && answer) {
      var sig = ks_giveaways_md5(answer + '|' + email);
      $('#giveaways_form').append($('<input />').attr('type', 'hidden').attr('name', 'giveaways_answer').val(answer));
      $('#giveaways_form').append($('<input />').attr('type', 'hidden').attr('name', 'giveaways_sig').val(sig));
    }
  });

  $('#giveaways_toggle_rules').click(function() {
    $('#giveaways_full_rules').toggle();
    $('#giveaways_toggle_rules').text($('#giveaways_full_rules').is(':visible') ? 'Hide official rules.' : 'Read official rules.');
  });

  $('#giveaways_email').blur(function() {
    $(this).mailcheck({
      suggested: function(element, suggestion) {
        if (suggestion.full) {
          $('#giveaways_email_hint a').text(suggestion.full);
          $('#giveaways_email_hint').fadeIn();
        } else {
          $('#giveaways_email_hint').fadeOut();
        }
      },
      empty: function() {
        $('#giveaways_email_hint').fadeOut();
      }
    });
  });

  $('#giveaways_email_hint a').click(function() {
    var address = $(this).text();

    if (address) {
      $('#giveaways_email').val(address);
    }
  });

  window.ks_giveaways_fb = function(url, title) {
    var width = 666;
    var height = 353;
    var shareurl = 'http://www.facebook.com/sharer.php?u='+encodeURIComponent(url)+'&t='+encodeURIComponent(title);

    window.open(shareurl, 'sharer', 'toolbar=0,status=0,width='+width+',height='+height);
  };

  window.ks_giveaways_tw = function(url, title, via) {
    var width = 700;
    var height = 500;
    var shareurl = "http://twitter.com/share?text="+encodeURIComponent(title)+"&count=none&counturl="+encodeURIComponent(url)+"&url="+url+"&via="+encodeURIComponent(via);

    window.open(shareurl, 'sharer', 'toolbar=0,status=0,width='+width+',height='+height);
  };

  window.ks_giveaways_li = function(url, title) {
    var width = 600;
    var height = 382;
    var shareurl = 'http://www.linkedin.com/shareArticle?mini=true&url='+encodeURIComponent(url)+'&title='+encodeURIComponent(title)+'&summary=';

    window.open(shareurl, 'sharer', 'toolbar=0,status=0,width='+width+',height='+height);
  };

  window.ks_giveaways_pi = function(url, title, media) {
    var width = 700;
    var height = 500;
    var shareurl = 'http://pinterest.com/pin/create/button/?url='+encodeURIComponent(url)+'&media='+encodeURIComponent(media)+'&description='+encodeURIComponent(title);

    window.open(shareurl, 'sharer', 'toolbar=0,status=0,width='+width+',height='+height);
  };

  var countdownEl = $('#countdown');
  if (countdownEl.length) {
    var until = countdownEl.data('until');

    if (until) {
      until = new Date(until * 1000);
      $('#countdown').countdown({until: until, compact: false, format: 'dHMS', alwaysExpire: true, onExpiry: window.location.reload});
    }
  }

});