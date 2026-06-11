import { readFileSync, writeFileSync, mkdirSync } from 'fs';
import { dirname, join } from 'path';
import plantumlEncoder from 'plantuml-encoder';

const BASE = 'C:\\Users\\Abdelhay\\Documents\\GitHub\\AttendanceFlow-AMS';

const files = [
  {
    src: join(BASE, 'Analyse', 'cas_utilisation', 'web', 'global.puml'),
    outputs: [
      join(BASE, 'docs', 'images', 'global-w.png'),
      join(BASE, 'Analyse', 'cas_utilisation', 'web', 'global-w.png'),
    ]
  },
  {
    src: join(BASE, 'Analyse', 'cas_utilisation', 'web', 'sprint1.puml'),
    outputs: [
      join(BASE, 'docs', 'images', 'sprint1.png'),
      join(BASE, 'Analyse', 'cas_utilisation', 'web', 'sprint1.png'),
    ]
  },
  {
    src: join(BASE, 'Analyse', 'cas_utilisation', 'web', 'sprint2.puml'),
    outputs: [
      join(BASE, 'docs', 'images', 'sprint2.png'),
      join(BASE, 'Analyse', 'cas_utilisation', 'web', 'sprint2.png'),
    ]
  },
  {
    src: join(BASE, 'Analyse', 'cas_utilisation', 'web', 'sprint3.puml'),
    outputs: [
      join(BASE, 'docs', 'images', 'sprint3.png'),
      join(BASE, 'Analyse', 'cas_utilisation', 'web', 'sprint3.png'),
    ]
  },
  {
    src: join(BASE, 'Analyse', 'cas_utilisation', 'mobile', 'global.puml'),
    outputs: [
      join(BASE, 'docs', 'images', 'global-m.png'),
      join(BASE, 'Analyse', 'cas_utilisation', 'mobile', 'global-m.png'),
    ]
  },
];

async function main() {
  const results = [];

  for (const { src, outputs } of files) {
    const source = readFileSync(src, 'utf-8');
    const encoded = plantumlEncoder.encode(source);
    const url = `https://www.plantuml.com/plantuml/png/${encoded}`;

    try {
      const response = await fetch(url);
      if (!response.ok) {
        results.push(`FAIL ${src} → HTTP ${response.status}`);
        continue;
      }
      const buffer = Buffer.from(await response.arrayBuffer());

      for (const outPath of outputs) {
        mkdirSync(dirname(outPath), { recursive: true });
        writeFileSync(outPath, buffer);
        results.push(`OK ${outPath}`);
      }
    } catch (err) {
      results.push(`FAIL ${src} → ${err.message}`);
    }
  }

  console.log(results.join('\n'));
}

main().catch(console.error);
