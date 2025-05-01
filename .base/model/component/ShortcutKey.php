<?php
\_::$Front->Finals[] = \MiMFa\Library\Html::Script('
document.addEventListener("keydown", function(event) {
    const elem = event.target;
    if (elem.matches("input, textarea")) {
        let rtl = /^[\s\d\-*\/\\\\\+\.?=_\]\[{}()&\^%\$#@!~`\'"<>|]*[\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF\uFB50-\uFDFF\uFE70-\uFEFF]$/g.test(elem.value);
        if (rtl || ((event.ctrlKey || event.altKey) && event.shiftKey)) {
            if (event.code.endsWith("Left")) {
                elem.dir = "ltr";
                elem.classList.add("be", "ltr");
                elem.classList.remove("rtl");
            } else if (rtl || event.code.endsWith("Right")) {
                elem.dir = "rtl";
                elem.classList.add("be", "rtl");
                elem.classList.remove("ltr");
            }
        }
    }
});
');
?>