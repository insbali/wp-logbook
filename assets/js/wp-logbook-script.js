jQuery(document).ready(function ($) {
  var switch_label = $(".wpl_switch_label");
  switch_label.on("click", function () {
    var label = $(".wpl_debug_status");
    var status = $("#debug_status");
    if (label.text() == "DISABLE") {
      label.text("ENABLE");
      // status.val("enable");
    } else {
      label.text("DISABLE");
      // status.val("disable");
    }
  });
});
