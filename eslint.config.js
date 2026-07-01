import blumilkDefault from '@blumilksoftware/eslint-config'

export default [
  ...blumilkDefault,
  {
    ignores: [
      'storage/**',
      'bootstrap/cache/**',
      'public/build/**',
      'vendor/**',
      'node_modules/**',
    ],
  },
]
