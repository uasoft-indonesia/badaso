const errorHandler = {
  badRequest(response) {
    console.log(response)
  },

  unauthenticated() {
    console.log(response)
  }
}

export default (error) => {
  const response = error.response

  if (typeof response === 'undefined') {
    console.log(response)
  }

  switch (response.status) {
    case 400:
      errorHandler.badRequest(response.data)
      break
    case 401:
      errorHandler.unauthenticated()
      break
  }

  return Promise.reject(error)
}
