import { Component } from '@angular/core';

interface PreviewTeacher {
  readonly name: string;
  readonly document: string;
  readonly subjects: readonly string[];
  readonly groups: readonly string[];
  readonly status: 'Activo' | 'Inactivo';
}

@Component({
  selector: 'app-profesores-table',
  templateUrl: './profesores-table.html',
  styleUrl: './profesores-table.scss',
})
export class ProfesoresTable {
  protected readonly previewTeachers: readonly PreviewTeacher[] = [
    {
      name: 'Docente Demo 01',
      document: 'DOC-DEMO-001',
      subjects: ['Matemáticas', 'Física'],
      groups: ['1.º A', '2.º B'],
      status: 'Activo',
    },
    {
      name: 'Docente Demo 02',
      document: 'DOC-DEMO-002',
      subjects: ['Lengua'],
      groups: ['3.º A', '3.º B'],
      status: 'Activo',
    },
    {
      name: 'Docente Demo 03',
      document: 'DOC-DEMO-003',
      subjects: ['Historia'],
      groups: ['5.º A'],
      status: 'Inactivo',
    },
  ];
}
