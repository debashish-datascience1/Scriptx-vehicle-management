/* eslint-disable prettier/prettier */
// API Confriguation

function getMeta(metaName) {
  const metas = document.getElementsByTagName("meta");

  for (let i = 0; i < metas.length; i++) {
    if (metas[i].getAttribute("name") === metaName) {
      return metas[i].getAttribute("content");
    }
  }
  return "";
}

export default {
  url: getMeta("API"),
  map: getMeta("mapApi"),
};
