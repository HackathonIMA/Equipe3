class School < ActiveRecord::Base
  before_save :update_from_ima

  has_many :shares
  has_many :users

  def School.from_ima(offset = 0, limit = 20, filters = {}, fields = {})
      ima = ImaApi.new('O4FK6qtxiu4m')
      ima.escolas(offset, limit, filters, fields)
  end

  def School.first_from_ima(filters, fields = {})
    School.from_ima(0, 1, filters, fields)[0]
  end

  private
  def update_from_ima
    if self.ima_id
      ima_school = School.first_from_ima({id: self.ima_id}, ['nomeUnidadeEscolar', 'endereco'])
      self.name = ima_school['nomeUnidadeEscolar']
      self.address = ima_school['endereco']
    else
      raise "ERROR: do not have ima_id"
    end
  end
end
