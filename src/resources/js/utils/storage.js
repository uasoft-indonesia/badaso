export default {
  getStorageDriver() {
    return process.env.MIX_FILESYSTEM_DRIVER
  },
  view(path)  {
    const driver = process.env.MIX_FILESYSTEM_DRIVER
    if (!path || !driver) return null
  
    if (driver === "s3") {
      var S3 = ""
      S3 = process.env.MIX_AWS_URL
      if (!S3) {
        S3 = `{https://${process.env.MIX_AWS_BUCKET}.s3-${process.env.MIX_AWS_DEFAULT_REGION}.amazonaws.com}`
      }
      return new URL(path, S3).toString()
    }
  
    if (driver === "public") {
      return new URL(`storage${path}`, window.location.origin).toString()
    }
  }
}