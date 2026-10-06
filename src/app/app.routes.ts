import { Routes } from '@angular/router';

export const routes: Routes = [
  {
    path: 'profesores',
    loadComponent: () =>
      import('./features/profesores/pages/profesores-page/profesores-page').then(
        (component) => component.ProfesoresPage,
      ),
    title: 'Profesores | Colegio Manantial',
  },
  {
    path: '',
    pathMatch: 'full',
    redirectTo: 'profesores',
  },
  {
    path: '**',
    redirectTo: 'profesores',
  },
];
