
Ext.define('Tualo.routes.SimpleUI', {
    statics: {
        load: async function () {
            return [
                {
                    name: 'todo/simpleui',
                    path: '#todo/simpleui'
                }
            ]
        }
    },
    url: 'todo/simpleui',
    handler: {
        action: function () {

            Ext.getApplication().addView('Tualo.tualojs.IFrame', {
                src: './todo/ui'
            });
        },
        before: function (action) {


            action.resume();
        }
    }
});