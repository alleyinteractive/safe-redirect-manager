const fs = require('fs');
const path = require('path');
const os = require('os');

module.exports = function getDevServer(mode, env) {
  const certPath = (env && env.certPath)
    ? env.certPath
    : path.join(
      os.homedir(),
      'broadway/config/nginx-config/certs'
    );
  const http = !! ((env && env.http));

  switch (mode) {
    case 'development':
      return {
        hot: true,
        quiet: false,
        noInfo: false,
        disableHostCheck: true,
        contentBase: '/client/build',
        port: 8080,
        headers: { 'Access-Control-Allow-Origin': '*' },
        stats: { colors: true },
        https: http ? false : {
          cert: fs.readFileSync(
            path.join(certPath, 'server.crt'),
            'utf8'
          ),
          key: fs.readFileSync(
            path.join(certPath, 'server.key'),
            'utf8'
          ),
        },
      };

    case 'production':
    default:
      return {};
  }
};
