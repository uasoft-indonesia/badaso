import resource from '../resource'
import endpoint from '../endpoint'

export default {
  login(data) {
    return resource.post(endpoint.auth.login, data)
  },

  logout() {
    return resource.post(endpoint.auth.logout)
  },

  forgotPassword(data) {
    return resource.post(endpoint.auth.forgotPassword, data)
  },

  resetPassword(data) {
    return resource.post(endpoint.auth.resetPassword, data)
  },

  register(data) {
    return resource.post(endpoint.auth.register, data)
  },

  verify(data) {
    return resource.post(endpoint.auth.verify, data)
  },

  refreshToken() {
    return resource.post(endpoint.auth.refreshToken)
  },

  user() {
    return resource.post(endpoint.auth.user)
  },

  changePassword(data) {
    return resource.post(endpoint.auth.changePassword, data)
  },

}
