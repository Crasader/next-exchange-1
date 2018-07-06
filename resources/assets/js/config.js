module.exports = {

    echo_url: '',
    user: '',

    set_echo_url: function () {

        /** Add the variable LARAVAL_ECHO=your-host:your-port-number in .env file **/

        $.ajax({
            url: '/set-echo-url',
            async: false,
            success: (response, state, error) => {

                module.exports.echo_url   = response;
            }
        });
    },

    get_echo_url: function () {

        return module.exports.echo_url;
    },

    set_user: function () {

        $.ajax({
            type: "GET",
            url: "/set-user",
            async: false,
            cache: false,
            success: (response, status, error) => {

                module.exports.user  = response;
            },

            error: function(data,status,error){
                console.log(error);
            }
        });
    },

    get_user: function () {

        return module.exports.user;
    }
};