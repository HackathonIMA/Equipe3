class CreateShares < ActiveRecord::Migration
  def change
    create_table :shares do |t|
      t.string :title, null: false
      t.string :description
      t.integer :category, null: false
      t.references :school, index: true, foreign_key: true, null: false
      t.references :user, index: true, foreign_key: true, null: false
      t.timestamp :date
      t.string :icon

      t.timestamps null: false
    end
  end
end
