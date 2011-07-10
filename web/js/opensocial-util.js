function showIframeModalBox(id, url)
{
  var modal = $(id);
  var modalContents = $(id + '_contents');
  var modalIframe = modalContents.getElementsByTagName('iframe')[0];
  var pos = getCenterMuchScreen(modalContents);
  modalContents.setStyle(pos);
  modalIframe.src = url;
  new Effect.Appear(modal, {from:0, to:0.7});
  new Effect.Appear(modalContents, {from:0, to:1.0});
}

function closeModalBox(id)
{
  var modal = $(id);
  var modalContents = $(id + '_contents');
  var modalIframe = modalContents.getElementsByTagName('iframe')[0];
  modal.style.display = 'none';
  modalContents.style.display = 'none';
  var modalIframeDoc = modalIframe.contentDocument || modalIframe.contentWindow.document;
  modalIframeDoc.open();
  modalIframeDoc.close();
}
