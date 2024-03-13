<?php

$iconNamez = $_GET["icons"] ?? $_GET["i"] ?? "all";
$iconsTheme = $_GET["theme"] ?? $_GET["t"] ?? "dark";
$perLine = $_GET["lines"] ?? $_GET["ln"] ?? 15;

const ONE_ICON = 48;
$SCALE = ONE_ICON / (300 - 44);

$shortNames = [
    'js' => 'javascript',
    'ts' => 'typescript',
    'py' => 'python',
    'tailwind' => 'tailwindcss',
    'vue' => 'vuejs',
    'nuxt' => 'nuxtjs',
    'go' => 'golang',
    'cf' => 'cloudflare',
    'wasm' => 'webassembly',
    'postgres' => 'postgresql',
    'k8s' => 'kubernetes',
    'next' => 'nextjs',
    'mongo' => 'mongodb',
    'md' => 'markdown',
    'ps' => 'photoshop',
    'ai' => 'illustrator',
    'pr' => 'premiere',
    'ae' => 'aftereffects',
    'scss' => 'sass',
    'sc' => 'scala',
    'net' => 'dotnet',
    'gatsbyjs' => 'gatsby',
    'gql' => 'graphql',
    'vlang' => 'v',
    'amazonwebservices' => 'aws',
    'bots' => 'discordbots',
    'express' => 'expressjs',
    'googlecloud' => 'gcp',
    'mui' => 'materialui',
    'windi' => 'windicss',
    'unreal' => 'unrealengine',
    'nest' => 'nestjs',
    'ktorio' => 'ktor',
    'pwsh' => 'powershell',
    'au' => 'audition',
    'rollup' => 'rollupjs',
    'rxjs' => 'reactivex',
    'rxjava' => 'reactivex',
    'ghactions' => 'githubactions',
    'sklearn' => 'scikitlearn',
];


function generateSvg($iconNames) {
    global $SCALE, $perLine, $iconsTheme, $shortNames;
    $iconSvgList = [];

    foreach ($iconNames as $name) {

        $iconPath = "icons/$name-$iconsTheme.svg";

        if (file_exists($iconPath)) {
            $iconSvgList[] = file_get_contents($iconPath);


        } elseif (array_key_exists($name, $shortNames)) {
            $iconPath = "icons/$shortNames[$name]-$iconsTheme.svg";
            if (file_exists($iconPath)) {
                $iconSvgList[] = file_get_contents($iconPath);

            } else {
                $iconPath = "icons/$shortNames[$name].svg";
                if (file_exists($iconPath)) {
                    $iconSvgList[] = file_get_contents($iconPath);
                }
            }


        } else {
            $iconPath = "icons/$name.svg";
            if (file_exists($iconPath)) {
                $iconSvgList[] = file_get_contents($iconPath);
            }
        }
    }


    $length = min($perLine * 300, count($iconNames) * 300) - 44;
    $height = ceil(count($iconSvgList) / $perLine) * 300 - 44;
    $scaledHeight = $height * $SCALE;
    $scaledWidth = $length * $SCALE;

    $svg = <<<SVG
        <svg width="$scaledWidth" height="$scaledHeight" viewBox="0 0 $length $height" fill="none" xmlns="http://www.w3.org/2000/svg">
          %s
        </svg>
SVG;

    $svgContent = implode('', array_map(function ($icon, $index) use ($perLine) {
        $x = ($index % $perLine) * 300;
        $y = floor($index / $perLine) * 300;
        return sprintf("<g transform=\"translate(%d, %d)\">%s</g>", $x, $y, $icon);
    }, $iconSvgList, range(0, count($iconSvgList) - 1)));

    return sprintf($svg, $svgContent);
}



$iconShortNames = [];
if ($iconNamez == 'all') {
    $svgIcons = [];
    $svgFiles = glob('icons/*.svg');

    foreach ($svgFiles as $svgFile) {
        $filename = basename(strtolower($svgFile));

        if (str_contains($filename, "-$iconsTheme.svg")) {
            $svgIcons[] = preg_replace('/-Light\.svg|-Dark\.svg$/i', '', $filename);

        } elseif (!str_contains($filename, "-light.svg") && !str_contains($filename, "-dark.svg")) {
            $svgIcons[] = preg_replace('/\.svg$/i', '', $filename);
        }
    }

    $iconShortNames = $svgIcons;

} else {
    $iconShortNames = explode(',', $iconNamez);
}


echo generateSvg($iconShortNames);
