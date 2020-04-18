/**
 * Welcome to your Workbox-powered service worker!
 *
 * You'll need to register this file in your web app and you should
 * disable HTTP caching for this file too.
 * See https://goo.gl/nhQhGp
 *
 * The rest of the code is auto-generated. Please don't update this file
 * directly; instead, make changes to your Workbox build configuration
 * and re-run your build process.
 * See https://goo.gl/2aRDsh
 */

importScripts("https://storage.googleapis.com/workbox-cdn/releases/4.3.1/workbox-sw.js");

importScripts(
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
  "/projects/drought/app/map/precache-manifest.38099b66e8a0d86c678764992ce48672.js"
=======
  "/projects/drought/app/map/precache-manifest.5bad8035dd606ea43ee9c3081ecf673f.js"
>>>>>>> parent of 826c7a7... Update src
=======
  "/projects/drought/app/map/precache-manifest.5bad8035dd606ea43ee9c3081ecf673f.js"
>>>>>>> parent of 826c7a7... Update src
=======
  "/projects/drought/app/map/precache-manifest.5bad8035dd606ea43ee9c3081ecf673f.js"
>>>>>>> parent of 826c7a7... Update src
);

self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});

workbox.core.clientsClaim();

/**
 * The workboxSW.precacheAndRoute() method efficiently caches and responds to
 * requests for URLs in the manifest.
 * See https://goo.gl/S9QRab
 */
self.__precacheManifest = [].concat(self.__precacheManifest || []);
workbox.precaching.precacheAndRoute(self.__precacheManifest, {});

workbox.routing.registerNavigationRoute(workbox.precaching.getCacheKeyForURL("/projects/drought/app/map/index.html"), {
  
  blacklist: [/^\/_/,/\/[^\/?]+\.[^\/]+$/],
});
