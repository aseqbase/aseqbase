class Evaluate{
    static SignPattern = /^\@/;
    static CodeFormat = "$(`{0}`).{1}()";

    static Url = function () {
        var urlParams = new URLSearchParams(window.location.search);
        for (let key of urlParams.keys())
            if (key.search(Evaluate.SignPattern) >= 0) {
                const act = key.replace(Evaluate.SignPattern, "");
                for (let value of urlParams.getAll(key))
                    eval(Evaluate.CodeFormat.replace("{0}", value).replace("{1}", act));
            }
    }
}