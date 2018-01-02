/**
 * Created by hdlovefork on 2017-12-12.
 */

layui.define(function (exports) {
    var hello = {
        hello: function () {
            console.log('Hello World');
        }
    };

    exports('mymod', hello);

});
