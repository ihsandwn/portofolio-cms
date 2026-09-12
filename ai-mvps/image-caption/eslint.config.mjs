import { defineConfig } from 'eslint/config';

export default defineConfig([
  {
    languageOptions: {
      ecmaVersion: 2022,
      sourceType: 'module',
      parserOptions: { project: './tsconfig.json' },
    },
    rules: {
      'no-console': 'off',
      'no-unused-vars': 'warn',
      'no-undef': 'off',
      'no-var': 'off',
      'prefer-arrow-callback': 'off',
    },
    ignores: ['**/.next/**', '**/build/**', '**/next-env.d.ts'],
  },
]);
