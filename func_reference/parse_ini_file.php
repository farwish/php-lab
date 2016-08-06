<?php
/**
 * parse_ini_file
 *
 * @farwish
 */

$fname = "demo.ini";

// 忽略块设置, 解析键值
print_r(parse_ini_file($fname));

// 包含块设置, 解析键值
print_r(parse_ini_file($fname, TRUE));

// 包含块设置, 不解析键值
print_r(parse_ini_file($fname, TRUE, INI_SCANNER_RAW));

echo "=============================\n";

$fname = "demo.ini";

/**
 * deal inherit ini
 * 用于处理含继承项的ini文件
 * 
 * @farwish
 */
function parse_inherit_ini($fname)
{
	$ini = parse_ini_file($fname, TRUE);

	$conf = [];

	if ($ini && is_array($conf)) {
		foreach ($ini as $namespace => $content) {

			list($current_namespace, $inherited_namespace) = explode(':', $namespace);

			$current_namespace = trim($current_namespace);
			$inherited_namesapce = trim($inherited_namespace);

			// 当前项
			$conf[$current_namespace] = $content;

			if ($inherited_namespace && $conf[$inherited_namespace]) {
				foreach ($conf[$inherited_namespace] as $k => $v) {
					// 判断用于:允许子项覆盖父项
					if (! $conf[$current_namespace][$k]) {
						$conf[$current_namespace][$k] = $v;
					}
				}
			}
		}
	}

	return $conf;
}

print_r(parse_inherit_ini($fname));
