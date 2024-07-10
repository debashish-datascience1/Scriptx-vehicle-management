module.exports = {
  filenameHashing: false,
  // add this line if project is being put in subdirectory of server ( doesn't support history mode )
  publicPath: "./",
  //
  css: {
    loaderOptions: {
      sass: {
        data: `
            @import "./src/assets/scss/global.scss";
          `
      }
    }
  }
};
