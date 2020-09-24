var wrapper = document.getElementById("signature-pad");
var clearButton = wrapper.querySelector("[data-action=clear]");
//var clear_all = wrapper.querySelector("[data-action=clear_all]");
var changeColorButton = wrapper.querySelector("[data-action=change-color]");
var change_the_color = wrapper.querySelector("[data-action=change_the_color]");
var undoButton = wrapper.querySelector("[data-action=undo]");
//var the_return = wrapper.querySelector("[data-action=the_return]");
var savePNGButton = wrapper.querySelector("[data-action=save-png]");
var the_png = wrapper.querySelector("[data-action=the_png]");
var saveJPGButton = wrapper.querySelector("[data-action=save-jpg]");
var the_jpg = wrapper.querySelector("[data-action=the_jpg]");
var saveSVGButton = wrapper.querySelector("[data-action=save-svg]");
var the_svg = wrapper.querySelector("[data-action=the_svg]");
//var select_width = wrapper.querySelector("[data-action=select_width]");
var canvas = wrapper.querySelector("canvas");

var signaturePad = new SignaturePad(canvas, {
  // It's Necessary to use an opaque color when saving image as JPEG;
  // this option can be omitted if only saving as PNG or SVG
  backgroundColor: 'rgba(255, 255, 255, 0)'
});	
// Adjust canvas coordinate space taking into account pixel ratio,
// to make it look crisp on mobile devices.
// This also causes canvas to be cleared.

function download(dataURL, filename) {
  if (navigator.userAgent.indexOf("Safari") > -1 && navigator.userAgent.indexOf("Chrome") === -1) {
    window.open(dataURL);
  } else {
    var blob = dataURLToBlob(dataURL);
    var url = window.URL.createObjectURL(blob);

    var a = document.createElement("a");
    a.style = "display: none";
    a.href = url;
    a.download = filename;

    document.body.appendChild(a);
    a.click();

    window.URL.revokeObjectURL(url);
  }
}

// One could simply use Canvas#toBlob method instead, but it's just to show
// that it can be done using result of SignaturePad#toDataURL.
function dataURLToBlob(dataURL) {
  // Code taken from https://github.com/ebidel/filer.js
  var parts = dataURL.split(';base64,');
  var contentType = parts[0].split(":")[1];
  var raw = window.atob(parts[1]);
  var rawLength = raw.length;
  var uInt8Array = new Uint8Array(rawLength);

  for (var i = 0; i < rawLength; ++i) {
    uInt8Array[i] = raw.charCodeAt(i);
  }

  return new Blob([uInt8Array], { type: contentType });
}

clearButton.addEventListener("click", function (event) {
  signaturePad.clear();
});

undoButton.addEventListener("click", function (event) {
  var data = signaturePad.toData();

  if (data) {
    data.pop(); // remove the last dot or line
    signaturePad.fromData(data);
  }
});

changeColorButton.addEventListener("click", function (event) {
  var r = Math.round(Math.random() * 255);
  var g = Math.round(Math.random() * 255);
  var b = Math.round(Math.random() * 255);
  var color = "rgb(" + r + "," + g + "," + b +")";

  signaturePad.penColor = color;
});

change_the_color.addEventListener("click", function (event) {

  var color = document.getElementById("color").style.backgroundColor;

  signaturePad.penColor = color;
});

savePNGButton.addEventListener("click", function (event) {
  if (signaturePad.isEmpty()) {
    alert("Please provide a signature first.");
  } else {
    var dataURL = signaturePad.toDataURL();
    download(dataURL, "signature.png");
  }
});

the_png.addEventListener("click", function (event) {
  if (signaturePad.isEmpty()) {
    alert("請先建立一個圖案");
  } else {
    var dataURL = signaturePad.toDataURL();
	download(dataURL, "signature.png");
  }
});

saveJPGButton.addEventListener("click", function (event) {
  if (signaturePad.isEmpty()) {
    alert("Please provide a signature first.");
  } else {
    var dataURL = signaturePad.toDataURL("image/jpeg");
    download(dataURL, "signature.jpg");
  }
});

the_jpg.addEventListener("click", function (event) {
  if (signaturePad.isEmpty()) {
    alert("請先建立一個圖案");
  } else {
    var dataURL = signaturePad.toDataURL("image/jpeg");
    download(dataURL, "signature.jpg");
  }
});

saveSVGButton.addEventListener("click", function (event) {
  if (signaturePad.isEmpty()) {
    alert("Please provide a signature first.");
  } else {
    var dataURL = signaturePad.toDataURL('image/svg+xml');
    download(dataURL, "signature.svg");
  }
});

the_svg.addEventListener("click", function (event) {
  if (signaturePad.isEmpty()) {
    alert("請先建立一個圖案");
  } else {
    var dataURL = signaturePad.toDataURL('image/svg+xml');
    download(dataURL, "signature.svg");
  }
});