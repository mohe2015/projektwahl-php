module.exports = {
  root: true,
  env: {
    browser: true,
    es2020: true
  },
  extends: [
    'standard'
  ],
  parser: '@typescript-eslint/parser',
  parserOptions: {
    project: "tsconfig.json",
    ecmaVersion: 11,
    sourceType: 'module'
  },
  plugins: [
    '@typescript-eslint'
  ],
}
