// the problem is how to store this. maybe store it in history state object?
// this would not work if you share url but it would probably work with reloading because the browser should store state???

// alternatively this could be a url parameter
// this would make the most sense on login as there usually is a redirect url
// the question is how to store it in the url because injection attacks would be bad
// mapping to a url should be possible but then ret() could also be just an url

// for more details things like prefilled data this wouldn't work
// maybe store path in url and data in history.state???
// would be a good fallback und pretty good default behaviour

const login = (ret) => {
    // bla bla

    // after successful login
    ret()
}

const register = (ret) => {


    // after successful register
    ret()
}

const index = () => {

}

const logout = () => {
    login(index)
}

const change_password = (ret) => {
    // show change password form

    // if unauthorized
    login(change_password(current_args))

    // if successful
    ret()
}

const add_user = (ret) => {
    // show add user form
    // event handlers etc.

    // if unauthorized call
    login(add_user(current_args))

    // if failed, allow retry

    // if successful call
    ret()
    // which is usually list_users
}