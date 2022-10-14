const replace = require("replace-in-file");
const ENTRY = "src/plugin/podlove-web-player.php";
const version = require("../package.json").version;

async function main() {
  await replace({
    files: ENTRY,
    from: /(Version:           \d\.\d\.\d?[-]?[\d])/,
    to: `Version:           ${version}`,
  });

  await replace({
    files: ENTRY,
    from: /(define\( \'PODLOVE_WEB_PLAYER_VERSION\'\, \'\d\.\d\.\d?[-]?[\d]\' \))/,
    to: `define( 'PODLOVE_WEB_PLAYER_VERSION', '${version}' )`,
  });
}

main();
