var IvaldiAdminFocusPoint = {
  image: null,
  input_x: null,
  input_y: null,

  init: function() {
    this.image = null;

    wp.media.view.Modal.prototype.on(
      "open",
      function(e) {
        this.image = jQuery(".media-modal img.details-image");
        this.input_x = jQuery('[data-name="focus_point_x"] input');
        this.input_y = jQuery('[data-name="focus_point_y"] input');

        jQuery(this.image).on(
          "load",
          function() {
            this.showFocusPoint(
              Math.round(this.image[0].width * this.input_x.val()),
              Math.round(this.image[0].height * this.input_y.val())
            );
          }.bind(this)
        );

        jQuery(this.input_x).on(
          "change",
          function(e) {
            this.updateFocusPoint();
          }.bind(this)
        );

        jQuery(this.input_y).on(
          "change",
          function(e) {
            this.updateFocusPoint();
          }.bind(this)
        );
      }.bind(this)
    );

    jQuery(document).on(
      "click",
      ".media-modal img.details-image",
      function(e) {
        var focus_point_x = e.pageX - this.image.offset().left;
        var focus_point_y = e.pageY - this.image.offset().top;

        this.showFocusPoint(focus_point_x, focus_point_y);
        this.updateFormFields(focus_point_x, focus_point_y);
      }.bind(this)
    );
  },

  showFocusPoint: function(x, y) {
    jQuery("#ivaldi-admin-focus-point").remove();

    var dot = jQuery(
      '<div id="ivaldi-admin-focus-point" style="background: rgba(0,0,0,.5); width: 40px; height: 40px; position: absolute; border-radius: 40px; border: 2px solid #fff;"></div>'
    );

    this.image.parent().css("position", "relative");

    var corrected_x =
      (this.image.parent().width() - this.image.width()) / 2 + x;

    dot.css("left", corrected_x);
    dot.css("top", y);

    this.image.parent().append(dot);
  },

  updateFormFields: function(x, y) {
    this.input_x.val(Math.round(x / this.image.width() * 100) / 100);
    this.input_y.val(Math.round(y / this.image.height() * 100) / 100);
    this.input_x.trigger("change"); // causes ajax save in the background
  },

  updateFocusPoint: function() {
    // limit x and y between 0 and 1
    this.input_x.val(Math.min(Math.max(0, this.input_x.val()), 1));
    this.input_y.val(Math.min(Math.max(0, this.input_y.val()), 1));

    // in case of NaN, set to 0
    if (isNaN(this.input_x.val())) {
      this.input_x.val(0);
    }
    if (isNaN(this.input_y.val())) {
      this.input_y.val(0);
    }

    var x = Math.round(this.input_x.val() * this.image.width());
    var y = Math.round(this.input_y.val() * this.image.height());

    this.showFocusPoint(x, y);
  }
};

jQuery(function() {
  IvaldiAdminFocusPoint.init();
});
