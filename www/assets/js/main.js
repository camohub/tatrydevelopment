$(function() {

	$('body').removeClass('init-color');

});

//naja.historyHandler.uiCache = false;
document.addEventListener('DOMContentLoaded', naja.initialize.bind(naja));

naja.registerExtension(LoaderExtension);
naja.registerExtension(LoaderExtension2);


function LoaderExtension(naja)
{
	naja.addEventListener('init', function () {
		this.loader = document.querySelector('#loader');
	}.bind(this));

	naja.addEventListener('start', showLoader.bind(this));
	naja.addEventListener('complete', hideLoader.bind(this));

	function showLoader() {
		console.log('show1111111111111111111111111');
		this.loader.style.display = 'block';
	}

	function hideLoader() {
		console.log('hide1111111111111111111111111');
		this.loader.style.display = 'none';
	}

	return this;
}

/**
 * This is only an example of implementation of Naja extensions. It can be done multiple times in code.
 * @param naja
 * @returns {LoaderExtension2}
 * @constructor
 */
function LoaderExtension2(naja)
{
	naja.addEventListener('start', showLoader.bind(this));
	naja.addEventListener('complete', hideLoader.bind(this));

	function showLoader() {
		console.log('show22222222222222222222222222');
	}

	function hideLoader() {
		console.log('hide22222222222222222222222222');
	}

	return this;
}

