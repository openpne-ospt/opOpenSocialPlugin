function showIframeModalBox(id, url)
{
  var modal = document.getElementById(id);
  var modalContents = document.getElementById(id + "_contents");
  var modalIframe = modalContents.getElementsByTagName('iframe')[0];
  modalIframe.src = url;
  new Effect.Appear(modal, {from:0, to:0.7});
  new Effect.Appear(modalContents, {from:0, to:1.0});
}
